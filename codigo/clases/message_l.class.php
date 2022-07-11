<?php


class Message_L {

    public static function getById($p_Id)       {

        $cnn = Registry::getInstance() -> DbConn;

        $p_Id = (integer)$p_Id;

        $row = $cnn -> Select_Fila("SELECT * FROM message WHERE men_Id = ? ORDER BY men_Id", array($p_Id));

        $object = null;
        if (!empty($row)) {
            $object = new Message_O();
            $object -> loadArray($row);
        }

        if ($row === false) {
            echo $cnn -> get_Error(Registry::getInstance() -> general['debug']);
        }

        return $object;
    }

    public static function getAllReceived()     {

        $cnn = Registry::getInstance() -> DbConn;
        $currentUser_Id  = Registry::getInstance()->Usuario->getId();

        $rows = $cnn -> Select_Lista("SELECT * FROM message WHERE men_Receiver_Id={$currentUser_Id} AND men_Is_Draft=0 AND men_Is_Scheduled=0 AND men_State_Sent=1 ORDER BY men_Id DESC");

        $object = null;
        $list   = null;

        //ROWS
        if ($rows) {
            $list = array();
            foreach ($rows as $row) {
                $object = new Message_O();
                $object -> loadArray($row);
                $list[] = $object;
            }
        }

        // ERRORS
        if ($rows === false) {
            echo $cnn -> get_Error(Registry::getInstance() -> general['debug']);
        }

        return $list;
    }

    public static function getAllSent()         {

        $cnn = Registry::getInstance() -> DbConn;
        $currentUser_Id  = Registry::getInstance()->Usuario->getId();

        $rows = $cnn -> Select_Lista("      SELECT      *  FROM        message 
                                            WHERE       men_Sender_Id       =   {$currentUser_Id} 
                                            AND         men_Is_Draft        =   0 
                                            AND         men_Is_Scheduled    =   0 
                                            AND         men_State_Sent      =   1
                                            ORDER BY    men_Id DESC
                                            ");

        $object = null;
        $list   = null;

        //ROWS
        if ($rows) {
            $list = array();
            foreach ($rows as $row) {
                $object = new Message_O();
                $object -> loadArray($row);
                $list[] = $object;
            }
        }

        // ERRORS
        if ($rows === false) {
            echo $cnn -> get_Error(Registry::getInstance() -> general['debug']);
        }

        return $list;
    }

    public static function getAllScheduled()    {

        $cnn = Registry::getInstance() -> DbConn;
        $currentUser_Id  = Registry::getInstance()->Usuario->getId();

        $rows = $cnn -> Select_Lista("      SELECT      *  
                                            FROM        message 
                                            WHERE       men_Sender_Id       =   {$currentUser_Id} 
                                            AND         men_Is_Draft        =   0 
                                            AND         men_Is_Scheduled    =   1 
                                            AND         men_State_Sent      =   0
                                            ORDER BY    men_Id DESC
                                            ");

        $object = null;
        $list   = null;

        //ROWS
        if ($rows) {
            $list = array();
            foreach ($rows as $row) {
                $object = new Message_O();
                $object -> loadArray($row);
                $list[] = $object;
            }
        }

        // ERRORS
        if ($rows === false) {
            echo $cnn -> get_Error(Registry::getInstance() -> general['debug']);
        }

        return $list;
    }

    public static function getAllScheduledCron()    {

        $cnn = Registry::getInstance() -> DbConn;

        $rows = $cnn -> Select_Lista("      SELECT      *  
                                            FROM        message 
                                            WHERE       men_Is_Scheduled    =   1
                                            ORDER BY    men_Id ASC
                                            ");

        $object = null;
        $list   = null;

        //ROWS
        if ($rows) {
            $list = array();
            foreach ($rows as $row) {
                $object = new Message_O();
                $object -> loadArray($row);
                $list[] = $object;
            }
        }

        // ERRORS
        if ($rows === false) {
            echo $cnn -> get_Error(Registry::getInstance() -> general['debug']);
        }

        return $list;
    }


    public static function getAllDrafts()       {

        $cnn = Registry::getInstance() -> DbConn;
        $currentUser_Id  = Registry::getInstance()->Usuario->getId();

        $rows = $cnn -> Select_Lista("      SELECT      *  
                                            FROM        message 
                                            WHERE       men_Sender_Id       =   {$currentUser_Id} 
                                            AND         men_Is_Draft        =   1 
                                            AND         men_Is_Scheduled    =   0 
                                            AND         men_State_Sent      =   0
                                            ORDER BY    men_Id DESC
                                            ");

        $object = null;
        $list   = null;

        //ROWS
        if ($rows) {
            $list = array();
            foreach ($rows as $row) {
                $object = new Message_O();
                $object -> loadArray($row);
                $list[] = $object;
            }
        }

        // ERRORS
        if ($rows === false) {
            echo $cnn -> get_Error(Registry::getInstance() -> general['debug']);
        }

        return $list;
    }

    public static function getAllChained($p_Id) {

        $cnn = Registry::getInstance() -> DbConn;
        $p_Id = (integer)$p_Id;
        $rows = $cnn -> Select_Lista("      SELECT      *  
                                            FROM        message 
                                            WHERE       men_Chained_Id={$p_Id} 
                                            ORDER BY    men_Id ASC
                                            ");

        $object = null;
        $list   = null;

        //ROWS
        if ($rows) {
            $list = array();
            foreach ($rows as $row) {
                $object = new Message_O();
                $object -> loadArray($row);
                $list[] = $object;
            }
        }

        // ERRORS
        if ($rows === false) {
            echo $cnn -> get_Error(Registry::getInstance() -> general['debug']);
        }

        return $list;
    }

    public static function getAllChainedQuantity($p_Id) {

        $cnn = Registry::getInstance() -> DbConn;
        $p_Id = (integer)$p_Id;


        $row = $cnn -> Select_Fila("SELECT COUNT(men_Id) as Cantidad FROM message WHERE men_Chained_Id={$p_Id}");

        $object = null;
        $list   = null;

        // ERRORS
        if ($row === false) {

            echo $cnn -> get_Error(Registry::getInstance() -> general['debug']);
        }

        return $row['Cantidad'];
    }

    /*

        public static function obtenerCantidad(){

            $cnn = Registry::getInstance() -> DbConn;

            $row = $cnn -> Select_Fila("SELECT COUNT(men_Id) as Cantidad FROM message ");

            if ($row === false) {// devuelve el error si algo fallo con MySql
                echo $cnn -> get_Error(Registry::getInstance() -> general['debug']);
            }

            return $row['Cantidad'];
        }

        public static function obtenerCantidadUnread(){

            $cnn = Registry::getInstance() -> DbConn;

            $row = $cnn -> Select_Fila("SELECT COUNT(men_Id) as Cantidad FROM message WHERE men_Visto=0");

            if ($row === false) {// devuelve el error si algo fallo con MySql
                echo $cnn -> get_Error(Registry::getInstance() -> general['debug']);
            }

            return $row['Cantidad'];
        }

        public static function obtenerTodosUnStatus(){

            $cnn = Registry::getInstance() -> DbConn;

            $rows = $cnn -> Select_Lista("SELECT * FROM message WHERE men_Visto=1 and men_Status=0");
            $object = null;
            $list = array();

            if ($rows) {
                foreach ($rows as $row) {
                    $object = new Message_O();
                    $object -> loadArray($row);
                    $list[] = $object;
                }
            } else {
                $list = $object;
            }
            if ($rows === false) {// devuelve el error si algo fallo con MySql
                echo $cnn -> get_Error(Registry::getInstance() -> general['debug']);
            }

            return $list;
        }

        public static function obtenerTodosHoy(){

            $cnn = Registry::getInstance() -> DbConn;

            $rows = $cnn -> Select_Lista("SELECT * FROM message WHERE men_Visto=0 and men_DisparadoHora like '".date('Y-m-d')."%' ");
            $object = null;
            $list = array();

            if ($rows) {
                foreach ($rows as $row) {
                    $object = new Message_O();
                    $object -> loadArray($row);
                    $list[] = $object;
                }
            } else {
                $list = $object;
            }
            if ($rows === false) {// devuelve el error si algo fallo con MySql
                echo $cnn -> get_Error(Registry::getInstance() -> general['debug']);
            }

            return $list;
        }


    */
}


