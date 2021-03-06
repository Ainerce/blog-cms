<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use app\models\Setting;
use app\modules\admin\models\Admin;
use app\modules\admin\models\Secret;

class IndexController extends Controller
{
	public $layout='admin';

	//进入各个页面之前判断是否已登陆，没有刚跳到登陆页面
	public function beforeAction($action)
	{
		if (empty(Yii::$app->session->get('valid_admin')))
		{
			$this->redirect(Url::to(['log/index']),3,"您未登陆，系统将在三秒后跳转到登陆页面");
		}
		return true;
	}

	//登陆显示欢迎界面
    public function actionIndex()
    {
    	$setting=new Setting();
    	$data['siteName']=$setting->getSiteName()->value;
    	$data['address']=$setting->getAddress()->value;
    	$data['nickName']=$setting->getNickName()->value;
    	$data['desc']=$setting->getDesc()->value;
        return $this->render('index',['info'=>$data]);
    }

    //修改密码界面
    public function actionSecret()
    {
    	$admin=new Secret(['scenario'=>'mod']);
    	if(Yii::$app->request->isPost)
    	{
    		if($admin->updSecret())
    		{
    			Yii::$app->session->destroy();
    			$this->redirect(Url::to(['log/index']));
    		}else
    		{
    			$this->redirect(Url::current());
    		}
    	}else
    	{
    		return $this->render('secret',['model'=>$admin]);
    	}	    	
    }

}
