<?php
/**
 * CodeIgniter Log Library
 *
 * @category   Applications
 * @package    CodeIgniter
 * @subpackage Libraries
 * @author     Bo-Yi Wu <appleboy.tw@gmail.com>
 * @license    BSD License
 * @link       http://blog.wu-boy.com/
 * @since      Version 1.0
 */
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'third_party/ci_log4php/Logger.php'; 

Logger::configure(APPPATH.'config/multiple.xml');  // ### add xml
class Lib_log
{
    /**
     * ci
     *
     * @param instance object
     */
    private $_ci;

    /**
     * log table name
     *
     * @param string
     */
    private $_log_table_name;
    public $log; // ### changes
    public $logdb; // ### changes

    public $levels = array(
        E_ERROR             => 'Error',
        E_WARNING           => 'Warning',
        E_PARSE             => 'Parsing Error',
        E_NOTICE            => 'Notice',
        E_CORE_ERROR        => 'Core Error',
        E_CORE_WARNING      => 'Core Warning',
        E_COMPILE_ERROR     => 'Compile Error',
        E_COMPILE_WARNING   => 'Compile Warning',
        E_USER_ERROR        => 'User Error',
        E_USER_WARNING      => 'User Warning',
        E_USER_NOTICE       => 'User Notice',
        E_STRICT            => 'Runtime Notice',
        E_RECOVERABLE_ERROR => 'Catchable error',
        E_DEPRECATED        => 'Runtime Notice',
        E_USER_DEPRECATED   => 'User Warning'
    );

    /**
     * constructor
     *
     */
    public function __construct()
    {
        $this->_ci =& get_instance();
        set_error_handler(array($this, 'error_handler'));
        set_exception_handler(array($this, 'exception_handler'));
       
        $this->logdb = Logger::getLogger('dberror');// for db log
    }

    /**
     * PHP Error Handler
     *
     * @param   int
     * @param   string
     * @param   string
     * @param   int
     * @return void
     */
    public function error_handler($severity, $message, $filepath, $line)
    {
        $data = array(
            'errno' => $severity,
            'errtype' => isset($this->levels[$severity]) ? $this->levels[$severity] : $severity,
            'errstr' => $message,
            'errfile' => $filepath,
            'errline' => $line,
            'user_agent' => $this->_ci->input->user_agent(),
            'ip_address' => $this->_ci->input->ip_address(),
            'time' => date('Y-m-d H:i:s')
        );

        self::setCustomMsg(array('errfile'=>$data['errfile'],'errline'=>$data['errline'],'errtype'=>$data['errtype']));
        $this->logdb->fatal($data['errstr']. ", at line no ".$data['errline']. " in ".$data['errfile']. " file"); //### db log
    
    }
    public static $custom_msg;
    public static function setCustomMsg($msg){
        self::$custom_msg = $msg;
    }

    public static function getCustomMsg(){
        return self::$custom_msg;
    }
    /**
     * PHP Error Handler
     *
     * @param   object
     * @return void
     */
    public function exception_handler($exception)
    {
        $data = array(
            'errno' => $exception->getCode(),
            'errtype' => isset($this->levels[$exception->getCode()]) ? $this->levels[$exception->getCode()] : $exception->getCode(),
            'errstr' => $exception->getMessage(),
            'errfile' => $exception->getFile(),
            'errline' => $exception->getLine(),
            'user_agent' => $this->_ci->input->user_agent(),
            'ip_address' => $this->_ci->input->ip_address(),
            'time' => date('Y-m-d H:i:s')
        );

        self::setCustomMsg(array('errfile'=>$data['errfile'],'errline'=>$data['errline'],'errtype'=>$data['errtype']));
        $this->logdb->fatal($data['errstr']. ", at line no ".$data['errline']. " in ".$data['errfile']. " file"); //### db log
    }
}

/* End of file Lib_log.php */
