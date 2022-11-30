<?php
class TraderLogic
{
    private $high;
    private $low;
    private $close;
    private $adx;
    public function __construct($high, $low, $close)
    {
        $this->high = $high;
        $this->low = $low;
        $this->close = $close;
    }
    public function getAdx()
    {
        return $this->adx;
    }
    public function setAdx($timePeriod = 0)
    {
        $this->adx = trader_adx($this->high, $this->low, $this->close, $timePeriod);
        return $this->adx;
    }
    public function getMacd($fastPeriod, $slowPeriod, $signalPeriod)
    {
        $this->adx = trader_adx($this->high, $this->low, $this->close, $timePeriod);
        return $this->adx;
    }
    public function getSignal($fastPeriod, $slowPeriod, $signalPeriod)
    {
        $this->adx = trader_adx($this->high, $this->low, $this->close, $timePeriod);
        return $this->adx;
    }
    public function getDivergence($fastPeriod, $slowPeriod, $signalPeriod)
    {
        $this->adx = trader_adx($this->high, $this->low, $this->close, $timePeriod);
        return $this->adx;
    }
    public function logic($adx, $dl_plus, $dl_minus, $macd)
    {
        /* สำหรับ ADX, MACD , DI+ and DI- */
        krsort($adx);
        $data = array();
        foreach ($adx as $keys => $val) {
            if ($val < 25) {
                array_push($data, "Correction period");
            } else if ($val >= 25) {
                if ($dl_plus[$keys] > $dl_minus[$keys] && $macd[$keys] > 0) {
                    array_push($data, "Uptrend");
                } else if ($dl_plus[$keys] > $dl_minus[$keys] && $macd[$keys] < 0) {
                    array_push($data, "Downtrend");
                } else {
                    array_push($data, "Correction period");
                }
            }
        }
        return $data;
    }
}
