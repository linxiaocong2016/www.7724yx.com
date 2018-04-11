<?php

/**
 * 扩展Yii::app()->db->createCommand($sql)，加入参数快速绑定
 *
 * @author Administrator
 */
class DBHelper {

    //put your code here
    public static function queryAll($sql, $params = array()) {
        try {
            $cmd = self::bindParam($sql, $params);
            return $cmd->queryAll();
        } catch( Exception $e ) {
            Tools::write_log(array($sql, $e->getMessage(), $e->getTraceAsString() ));
        }
    }

    public static function queryRow($sql, $params = array()) {
        try {
            $cmd = self::bindParam($sql, $params);
            return $cmd->queryRow();
        } catch( Exception $e ) {
            Tools::write_log(array($sql, $e->getMessage(), $e->getTraceAsString() ));
        }
    }

    public static function execute($sql, $params = array()) {
        try {
            $cmd = self::bindParam($sql, $params);
            return $cmd->execute();
        } catch( Exception $e ) {
            Tools::write_log(array($sql, $e->getMessage(), $e->getTraceAsString() ));
        }
    }

    private static function bindParam($sql, $params = array()) {
        $cmd = Yii::app()->db->createCommand($sql);

        foreach( $params as $k => $v )
            $cmd->bindValue($k, $v);

        return $cmd;
    }

    /**
     * UCenter
     * @param type $sql
     * @param type $params
     * @return type
     */
    public static function uc_queryAll($sql, $params = array()) {
        try {
            $cmd = self::uc_bindParam($sql, $params);
            return $cmd->queryAll();
        } catch( Exception $e ) {
            Tools::write_log(array($sql, $e->getMessage(), $e->getTraceAsString() ));
        }
    }

    public static function uc_queryRow($sql, $params = array()) {
        try {
            $cmd = self::uc_bindParam($sql, $params);
            return $cmd->queryRow();
        } catch( Exception $e ) {
            Tools::write_log(array($sql, $e->getMessage(), $e->getTraceAsString() ));
        }
    }

    public static function uc_execute($sql, $params = array()) {
        try {
            $cmd = self::uc_bindParam($sql, $params);
            return $cmd->execute();
        } catch( Exception $e ) {
            Tools::write_log(array($sql, $e->getMessage(), $e->getTraceAsString() ));
        }
    }

    private static function uc_bindParam($sql, $params = array()) {

        $cmd = Yii::app()->ucdb->createCommand($sql);

        foreach( $params as $k => $v )
            $cmd->bindValue($k, $v);

        return $cmd;
    }

    /**
     * seven
     * @param $sql
     * @param array $params
     * @return mixed
     */
    public static function seven_queryAll($sql, $params = array()) {
        try {
            $cmd = self::seven_bindParam($sql, $params);
            return $cmd->queryAll();
        } catch( Exception $e ) {
            Tools::write_log(array($sql, $e->getMessage(), $e->getTraceAsString() ));
        }
    }

    public static function seven_queryRow($sql, $params = array()) {
        try {
            $cmd = self::seven_bindParam($sql, $params);
            return $cmd->queryRow();
        } catch( Exception $e ) {
            Tools::write_log(array($sql, $e->getMessage(), $e->getTraceAsString() ));
        }
    }

    public static function seven_execute($sql, $params = array()) {
        try {
            $cmd = self::seven_bindParam($sql, $params);
            return $cmd->execute();
        } catch( Exception $e ) {
            Tools::write_log(array($sql, $e->getMessage(), $e->getTraceAsString() ));
        }
    }

    private static function seven_bindParam($sql, $params = array()) {
        $cmd = Yii::app()->seven->createCommand($sql);

        foreach( $params as $k => $v )
            $cmd->bindValue($k, $v);

        return $cmd;
    }


}
