<?php
namespace Glomado\Bookly\Backend\Modules\Services;
use Bookly\Backend\Modules\Services\Ajax;
use Bookly\Backend\Modules\Services\Proxy;
use Bookly\Lib;

class Glomado_Services extends Ajax {
    public static function getServices()
    {
        $columns = self::parameter( 'columns' );
        $order   = self::parameter( 'order', array() );
        $filter  = self::parameter( 'filter' );
        $limits  = array(
            'length' => self::parameter( 'length' ),
            'start'  => self::parameter( 'start' ),
        );

        $query = Lib\Entities\Service::query( 's' )
            ->select( 's.*, c.name AS category_name' )
            ->leftJoin( 'Category', 'c', 'c.id = s.category_id' )
            ->whereIn( 's.type', array_keys( Proxy\Shared::prepareServiceTypes( array( Lib\Entities\Service::TYPE_SIMPLE => Lib\Entities\Service::TYPE_SIMPLE ) ) ) );

        foreach ( $order as $sort_by ) {
            $query->sortBy( str_replace( '.', '_', $columns[ $sort_by['column'] ]['data'] ) )
                ->order( $sort_by['dir'] == 'desc' ? Lib\Query::ORDER_DESCENDING : Lib\Query::ORDER_ASCENDING );
        }

        $total = $query->count();

        if ( $filter['category'] != '' ) {
            $query->where( 's.category_id', $filter['category'] );
        }

        if ( $filter['search'] != '' ) {
            $fields = array();
            foreach ( $columns as $column ) {
                switch ( $column['data'] ) {
                    case 'category_name':
                        $fields[] = 'c.name';
                        break;
                    case 'id':
                    case 'title':
                        $fields[] = 's.' . $column['data'];
                        break;
                }
            }

            $search_columns = array();
            foreach ( $fields as $field ) {
                $search_columns[] = $field . ' LIKE "%%%s%"';
            }
            if ( ! empty( $search_columns ) ) {
                $query->whereRaw( implode( ' OR ', $search_columns ), array_fill( 0, count( $search_columns ), $filter['search'] ) );
            }
        }

        $filtered = $query->count();

        if ( ! empty( $limits ) ) {
            $query->limit( $limits['length'] )->offset( $limits['start'] );
        }

        $type_icons = Proxy\Shared::prepareServiceIcons( array( Lib\Entities\Service::TYPE_SIMPLE => 'far fa-calendar-check' ) );

        $data = array();
        foreach ( $query->fetchArray() as $service ) {
            $sub_services_count = count( Lib\Entities\Service::find( $service['id'] )->getSubServices() );
            $data[] = array(
                'id'            => $service['id'],
                'title'         => $service['title'],
                'position'      => sprintf( '%05d-%05d', $service['position'], $service['id'] ),
                'category_name' => $service['category_name'],
                'colors'        => Proxy\Shared::prepareServiceColors( array_fill( 0, 3, $service['color'] ), $service['id'], $service['type'] ),
                'type'          => ucfirst( $service['type'] ),
                'type_icon'     => $type_icons[ $service['type'] ],
                'price'         => Lib\Utils\Price::format( $service['price'] ),
                'duration'      => in_array( $service['type'], array( Lib\Entities\Service::TYPE_COLLABORATIVE, Lib\Entities\Service::TYPE_COMPOUND ) )
                    ? sprintf( _n( '%d service', '%d services', $sub_services_count, 'bookly' ), $sub_services_count )
                    : Lib\Utils\DateTime::secondsToInterval( $service['duration'] ),
                'online_meetings' => $service['online_meetings'],
            );
        }

        return array(
            'draw'            => ( int ) self::parameter( 'draw' ),
            'data'            => $data,
            'recordsTotal'    => $total,
            'recordsFiltered' => $filtered,
        );
    }
}