<?php
namespace app\components;

use app\modules\admin\models\Eventlog;
use yii\base\Widget;

class InfoBadge extends Widget
{
    public $type;

    public function init(){
        parent::init();
        if($this->type===null) $this->type = 'event';
    }
    public function run(){
        switch ($this->type){
            case 'event':
                return $this->GetEventCount();
                break;
            case 'count':
                return Eventlog::find()->count();
                break;
            case 'group':
                return $this->GetEventGroup();
                break;
            case 'system':
                return $this->SysState();
                break;
        }
    }

    private function GetEventCount(){
        $count = Eventlog::find()->count();
        return '<span class="badge">'.$count.'</span>';
    }

    private function GetEventGroup(){
        $types = ['error','info','access'];
        $content = '';
        foreach ($types as $type){
            $count = Eventlog::find()->where(['type'=>$type])->count();
            if($type=='error'){
                $content .= '<li>
                                    <a href="/admin/events/view?type=error">
                                        <div class="clearfix">
													<span class="pull-left">
														<i class="btn btn-xs no-hover btn-danger fa fa-exclamation-triangle"></i>
														Ошибки (error)
													</span>
                                            <span class="pull-right badge badge-info">' . $count . '</span>
                                        </div>
                                    </a>
                                </li>';
            }
            if($type=='info'){
                $content .= '<li>
                                    <a href="/admin/events/view?type=info">
                                        <div class="clearfix">
													<span class="pull-left">
														<i class="btn btn-xs no-hover btn-info fa fa-info-circle"></i>
														Информация (info)
													</span>
                                            <span class="pull-right badge badge-info">' . $count . '</span>
                                        </div>
                                    </a>
                                </li>';
            }
            if($type=='access'){
                $content .= '<li>
                                    <a href="/admin/events/view?type=access">
                                        <div class="clearfix">
													<span class="pull-left">
														<i class="btn btn-xs no-hover btn-success fa fa-shield"></i>
														Доступ (access)
													</span>
                                            <span class="pull-right badge badge-info">' . $count . '</span>
                                        </div>
                                    </a>
                                </li>';
            }
        }
        return $content;
    }

    protected function SysState(){
        //memory stat
        $stat['mem_percent'] = round(shell_exec("free | grep Mem | awk '{print $3/$2 * 100.0}'"),0);
        $mem_result = shell_exec("cat /proc/meminfo | grep MemTotal");
        $stat['mem_total'] = round(preg_replace("#[^0-9]+(?:\.[0-9]*)?#", "", $mem_result) / 1024 / 1024, 3);
        $mem_result = shell_exec("cat /proc/meminfo | grep MemFree");
        $stat['mem_free'] = round(preg_replace("#[^0-9]+(?:\.[0-9]*)?#", "", $mem_result) / 1024 / 1024, 3);
        $stat['mem_used'] = $stat['mem_total'] - $stat['mem_free'];
        //hdd stat
        $stat['hdd_free'] = round(disk_free_space("/") / 1024 / 1024 / 1024, 2);
        $stat['hdd_total'] = round(disk_total_space("/") / 1024 / 1024/ 1024, 2);
        $stat['hdd_used'] = $stat['hdd_total'] - $stat['hdd_free'];
        $stat['hdd_percent'] = round(sprintf('%.2f',($stat['hdd_used'] / $stat['hdd_total']) * 100), 0);

        $content = '';

        if($stat['mem_percent']<50)
            $bar_state_mem='progress-bar progress-bar-info';
        else if($stat['mem_percent']>49&&$stat['hdd_percent']<75) {
            $bar_state_mem = 'progress-bar progress-bar-warning';
        }
        else {
            $bar_state_mem = 'progress-bar progress-bar-danger';
        }
        if($stat['hdd_percent']<55)
            $bar_state_hdd='progress-bar progress-bar-info';
        else if($stat['hdd_percent']>54&&$stat['hdd_percent']<85) {
            $bar_state_hdd = 'progress-bar progress-bar-warning';
        }
        else {
            $bar_state_hdd='progress-bar progress-bar-danger';
        }
        $content.='<li>
                                    <a href="#">
                                        <div class="clearfix">
                                            <span class="pull-left">Занято на диске</span>
                                            <span class="pull-right">'.$stat['hdd_percent'].'%</span>
                                        </div>

                                        <div class="progress progress-mini">
                                            <div style="width:'.$stat['hdd_percent'].'%" class="'.$bar_state_hdd.'"></div>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <div class="clearfix">
                                            <span class="pull-left">Занято памяти</span>
                                            <span class="pull-right">'.$stat['mem_percent'].'%</span>
                                        </div>

                                        <div class="progress progress-mini">
                                            <div style="width:'.$stat['mem_percent'].'%" class="'.$bar_state_mem.'"></div>
                                        </div>
                                    </a>
                                </li>';

        return $content;
    }

}