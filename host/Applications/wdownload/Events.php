<?php
/**
 * This file is part of workerman.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the MIT-LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @author walkor<walkor@workerman.net>
 * @copyright walkor<walkor@workerman.net>
 * @link http://www.workerman.net/
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */

/**
 * 用于检测业务代码死循环或者长时间阻塞等问题
 * 如果发现业务卡死，可以将下面declare打开（去掉//注释），并执行php start.php reload
 * 然后观察一段时间workerman.log看是否有process_timeout异常
 */
//declare(ticks=1);

use \GatewayWorker\Lib\Gateway;

/**
 * 主逻辑
 * 主要是处理 onConnect onMessage onClose 三个方法
 * onConnect 和 onClose 如果不需要可以不用实现并删除
 */
class Events
{
    /**
     * 当客户端连接时触发
     * 如果业务不需此回调可以删除onConnect
     * 
     * @param int $client_id 连接id
     */
     /*
    public static function onConnect($client_id)
    {
        // 向当前client_id发送数据 
        //Gateway::sendToClient($client_id, "Hello $client_id\r\n");
        // 向所有人发送
        //Gateway::sendToAll("$client_id login\r\n");
    }
    */
    
   /**
    * 当客户端发来消息时触发
    * @param int $client_id 连接id
    * @param mixed $message 具体消息
    */
   public static function onMessage($client_id, $message)
   {
        //引入文件
        $dirr = dirname(__FILE__).'/data.conf.php';
        include($dirr);
        $getData = json_decode($message,true);
        if ($getData['token'] != $data['token']) {
        	$error = '{"type":"0"}';
        	GateWay::sendToClient($client_id,$error);
        	return GateWay::closeClient($client_id);
        }else {//token正确时进行以下内容
        	GateWay::bindUid($client_id,$data['name']);
        	if ($getData['type'] == 'login') {
        		if ($getData['devices'] == 'phone') {
        			// code...
        			$loginSucceed = '{"type":"1","name":"'.$data['name'].'","devices":"phone"}';
        			//return $loginSucceed;
        		}elseif($getData['devices'] == 'pc'){
        			$loginSucceed = '{"type":"1","name":"'.$data['name'].'","devices":"pc"}';
        			//return $loginSucceed;
        		}
        		// 登陆成功返回1
        		GateWay::sendToUid($data['name'],$loginSucceed);
        	}elseif ($getData['task'] == 'get') {
        		$sendTask = $tasks;
        		GateWay::sendToUid($data['name'],$sendTask);
        	}elseif($getData['filepath'] == 'get'){
        		GateWay::sendToUid($data['name'],$filepath);
        	}elseif($getData['dltime'] == 'get'){
        		GateWay::sendToUid($data['name'],$dltime);
        	}elseif($getData['dlspeed'] == 'get'){
        		GateWay::sendToUid($data['name'],$dlspeed);
        	}elseif($getData['taskacton'] == 'creat'){
        		//获取上传任务的主要内容
        		$getYs = $getData['creatdata'];
        		//引入包含的文件data.conf.php
        		include __DIR__.'/data.conf.php';
				$geshi = $getYs;
				$task = json_decode($tasks,true);
				function add_dl($ys,$recv){
					$no = sizeof($ys['data']['dldata']);
					$a = array("title"=>$recv['title'],"url"=>$recv['url'],"dltype"=>1,"type"=>"dl","id"=>$no);
					$b = json_encode($a,320);
					$d = array("array"=>$a,"string"=>$b);
					return $d;
				}
				$bc = add_dl($task,$geshi);
				$task['data']['dldata'][] = $bc['array'];
				$rp = json_encode($task,320);
				$b = file_get_contents(__DIR__.'/data.conf.php');
				$a = str_replace($tasks, $rp, $b);
				file_put_contents(__DIR__.'/data.conf.php', $a);
				include __DIR__.'/data.conf.php';
				$ys_1 = json_decode($tasks,true);
				$ys_2 = $ys_1['data'];
				$ys_3 = json_encode($ys_2,320);
				$fs = '{"data":'.$ys_3.',"creat":"1"}';
        		//开始组装发送给受控端文件的字符串
        		$sendMsg = '{"task":"creat","url":"'.$getYs['url'].'","title":"'.$getYs['title'].'","devices":"pc","creatid":'.$newId.'}';
        		//GateWay::sendToClient($client_id,$sendMsg);
        		GateWay::sendToUid($data['name'],$sendMsg);
        		GateWay::sendToUid($data['name'],$fs);
        	}elseif($getData['type'] == 'ping'){
        		$backPing = '{"rec":"1"}';
        		GateWay::sendToUid($data['name'],$backPing);
        	}elseif($getData['type'] == 'delDl'){
        		include __DIR__.'/data.conf.php';
				$allTask = json_decode($tasks, true);
				#$rec = '{"token":"123456","type":"delDl","id":"2"}';
				$delId = $getData['id'];
				$dlTask = $allTask['data']['dldata'];
				$oldTask = json_encode($dlTask, 320);
				$zuHe = array();
				foreach ($dlTask as $item => $i) {
				    $task = $dlTask[$item];
				    $title = $task['title'];
				    $url = $task['url'];
				    $id = $task['id'];
				    if ($id != $delId) {
				        if ((int)$id > (int)$delId) {
				            $newId = $delId++;
				        } else {
				            $newId = $id;
				        }
				        $zuhe = '{"title":"'.$title.'","url":"'.$url.'","dltype":1,"type":"dl","id":'.$newId.'}';
				        $fcZuhe = json_decode($zuhe, true);
				        $zuHe[] = $fcZuhe;
				    }
				}
				$final = json_encode($zuHe, 320);
				$b = file_get_contents(__DIR__.'/data.conf.php');
				$a = str_replace($oldTask, $final, $b);
				file_put_contents(__DIR__.'/data.conf.php', $a);
				include __DIR__.'/data.conf.php';
        		GateWay::sendToUid($data['name'],$tasks);
        	}elseif($getData['finishid'] != ''){
        		include __DIR__.'/data.conf.php';
				$allTask = json_decode($tasks, true);
				$delId = $getData['finishid'];
				$dlTask = $allTask['data']['dldata'];
				$oldTask = json_encode($dlTask, 320);
				$zuHe = array();
				foreach ($dlTask as $item => $i) {
				    $task = $dlTask[$item];
				    $title = $task['title'];
				    $url = $task['url'];
				    $id = $task['id'];
				    if ($id != $delId) {
				        if ((int)$id > (int)$delId) {
				            $newId = $delId++;
				       } else {
				           $newId = $id;
				       }
				       $zuhe = '{"title":"'.$title.'","url":"'.$url.'","dltype":1,"type":"dl","id":'.$newId.'}';
				       $fcZuhe = json_decode($zuhe, true);
				       $zuHe[] = $fcZuhe;
				   }
				}
				$final = json_encode($zuHe, 320);
				$b = file_get_contents(__DIR__.'/data.conf.php');
				$a = str_replace($oldTask, $final, $b);
				file_put_contents(__DIR__.'/data.conf.php', $a);
				$geshi = $dlTask[(int)$delId];
				$c = $allTask['data']['dleddata'];
				$newId = sizeof($c);
				if($c == 0){
					$yuanshi = '';
				}else{
					$yuanshi = json_encode($c,320);
				}
				$zuzhuang = '{"title":"'.$geshi['title'].'","url":"'.$geshi['url'].'","dltype":"1","type":"dled","id":'.$newId.'}';
				$strZuzhuang = json_decode($zuzhuang,true);
				$c[] = $strZuzhuang;
				$chongxie = json_encode($c,320);
				$b = file_get_contents(__DIR__.'/data.conf.php');
				$a = str_replace($yuanshi,$chongxie,$b);
				file_put_contents(__DIR__.'/data.conf.php',$a);
				include __DIR__.'/data.conf.php';
				$retask = array("retask"=>json_decode($tasks,true));
				$pb = json_encode($retask,320);
				GateWay::sendToUid($data['name'],$pb);
        	}elseif($getData['type'] == 'delDled'){
        		include __DIR__.'/data.conf.php';
				$allTask = json_decode($tasks, true);
				#$rec = '{"token":"123456","type":"delDl","id":"2"}';
				$delId = $getData['id'];
				$dlTask = $allTask['data']['dleddata'];
				$oldTask = json_encode($dlTask, 320);
				$zuHe = array();
				foreach ($dlTask as $item => $i) {
				    $task = $dlTask[$item];
				    $title = $task['title'];
				    $url = $task['url'];
				    $id = $task['id'];
				    if ($id != $delId) {
				        if ((int)$id > (int)$delId) {
				            $newId = $delId++;
				        } else {
				            $newId = $id;
				        }
				        $zuhe = '{"title":"'.$title.'","url":"'.$url.'","dltype":1,"type":"dled","id":'.$newId.'}';
				        $fcZuhe = json_decode($zuhe, true);
				        $zuHe[] = $fcZuhe;
				    }
				}
				$final = json_encode($zuHe, 320);
				$b = file_get_contents(__DIR__.'/data.conf.php');
				$a = str_replace($oldTask, $final, $b);
				file_put_contents(__DIR__.'/data.conf.php', $a);
				include __DIR__.'/data.conf.php';
        		GateWay::sendToUid($data['name'],$tasks);
        	}
        }
        
   }
   
   /**
    * 当用户断开连接时触发
    * @param int $client_id 连接id
    */
   public static function onClose($client_id)
   {
       // 向所有人发送 
       //GateWay::sendToAll("$client_id logout\r\n");
   }
}
