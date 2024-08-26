<?php

namespace App\Helpers;

class Response{
    const STATUS_SUCCESS        = 200;
    const STATUS_CREATED        = 201;
    const STATUS_REDIRECT       = 302;
    const STATUS_ERROR          = 400;
    const STATUS_NOT_FOUND      = 404;
    const STATUS_FORBIDDEN      = 403;
    const STATUS_UNAUTHENTICATED= 401;

    protected $status;
    protected $items;
    protected $paginate;
    protected $template;

    /**
     * @param string $message message key to translate for response message.
     * @param array $replacers key/value pair for items to replace in message translations.
     * @param string $type define if output needs to be json or view template.
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function submit( $message, $replacers=[], $type='json' ){
        $response   = [
            'status'    => $this->getStatus() ? $this->getStatus() : self::STATUS_SUCCESS,
            'message'   => __( $message, $replacers )
        ];

        $items      = $this->getItems();
        if( $items && !empty( $items ) ){
            $response['content']    = $this->getItems();
        }

        $pagination = $this->getPagination();
        if( $pagination && !empty( $pagination ) ){
            $response['paginate']   = $pagination;
        }

        if( $type == 'template' ){
            $template   = $this->getTemplate();
            $response   = [ 'response' => json_encode( $response ), 'data' => $response ];
            return response()->view( $template ? $template : 'common.not_found', $response );
        }

        return response()->json( $response );
    }

    /**
     * @param string $message message key to translate for response message.
     * @param array $replacers key/value pair for items to replace in message translations.
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function view( $message, $replacers=[] ){
        return $this->submit( $message, $replacers, 'template' );
    }

    /**
     * @param string $status status value to set for JSON status value
     * @return Response response object
     */
    public function status( $status ){
        switch( $status ){
            case 'redirect':
                $this->status = self::STATUS_REDIRECT;
            break;
            case 'error':
                $this->status = self::STATUS_ERROR;
                break;
            case 'notfound':
                $this->status = self::STATUS_NOT_FOUND;
                break;
            case 'forbidden':
                $this->status = self::STATUS_FORBIDDEN;
                break;
            case 'unauthenticated':
                $this->status = self::STATUS_UNAUTHENTICATED;
                break;
            case 'created':
                $this->status = self::STATUS_CREATED;
                break;
            default:
                $this->status = self::STATUS_SUCCESS;
                break;
        }

        return $this;
    }

    /**
     * @return int status code for response.
     */
    public function getStatus(){
        return $this->status != '' ? $this->status : self::STATUS_ERROR;
    }

    /**
     * @param object|array $items body contents for the response
     */
    public function items( $items ){
        $this->setPagination( $items );

        if( utils()->isPagination( $items ) ){
            $this->items    = $items->toArray()['data'];
        }
        else{
            $this->items    = $items;
        }

        return $this;
    }

    /**
     * @return object|array
     */
    public function getItems(){
        return $this->items;
    }

    /**
     * @param array|object $items array or objects based on which pagination is generated.
     * @return Response $this
     */
    public function setPagination( $items ){
        if( utils()->isCollection( $items ) ){
            // We may use this space to create custom pagination for collections.
        }
        elseif( utils()->isPagination( $items ) ){
            $this->paginate = $items;
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getPagination(){
        return !empty( $this->paginate ) && $this->paginate->currentPage() > 0 ? [
            'current'   => $this->paginate->currentPage(),
            'per_page'  => $this->paginate->perPage(),
            'pages'     => $this->paginate->lastPage(),
            'total'     => $this->paginate->total()
        ] : [];
    }

    /**
     * @param $template
     * @return Response
     */
    public function template( $template ){
        $this->template = $template;
        return $this;
    }

    /**
     * @return string
     */
    public function getTemplate(){
        return $this->template;
    }
}
