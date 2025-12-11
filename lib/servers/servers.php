<?php
class servers
{
    /**
     *
     */
    public function __construct()
    {
    }

    /**
     *
     */
    public function __destruct()
    {
    }
    
    /**
     * Set friendly columns\' names to order tables\' entries
     */
    public function setOrderingValues()
    {
        $ordering = [
            'id' => 'id',
            'limitsetflash' => 'Limit Flash',
            'limitsetfrp' => 'Limit Frp',
            'limitsetfdl' => 'Liit Fdl',
            'state' => 'status'

        ];

        return $ordering;
    }
}
?>