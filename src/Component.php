<?php

namespace yii\apostle;

use Apostle\Mail;
use Apostle\Queue;

class Component extends \CApplicationComponent
{
    public $domainKey;
    public $from;
    public $enabled = true;
    
    public function init()
    {
        \Apostle::setup($this->domainKey);
    }
    
    /**
     * 
     * @param string $template template slug
     * @param string|array $to receiver email
     * @param array $data
     * @return void
     * @throws Exception
     */
    public function send($template, $to, $data = array())
    {
        \Yii::log(
            sprintf(
                "Sending %s to %s\t%s",
                $template,
                is_string($to) ? $to : implode(', ', $to),
                json_encode($data)
            ),
            \CLogger::LEVEL_INFO,
            'email'
        );
        
        if (!$this->enabled)
            return;
        
        $data += array(
            'from' => $this->from,
            'appName' => \Yii::app()->name,
        );
        
        $failures = array();
        $failure = null;
        
        if (is_array($to)) {
            $queue = new Queue();
            
            foreach ($to as $email) {
                $mail = $this->newMail($template, $email, $data);
                $queue->add($mail);
            }
            
            $queue->deliver($failures);
            
            if ($failures)
                $failure = $failures[0]->deliveryError();
            
        } else {
            $mail = $this->newMail($template, $to, $data);
            $mail->deliver($failure);
        }
        
        if ($failure)
            throw new Exception($failure);
    }
    
    /**
     * 
     * @param type $template
     * @param type $to
     * @param array $data
     * @return \Apostle\Mail
     */
    private function newMail($template, $to, $data)
    {
        $data += array('email' => $to);
        
        return new Mail($template, $data);
    }
}
