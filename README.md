# W-Download
## 1，什么是W-Download？
W-Download 是一款用来远程下载的，部署在两端，运行在三端的“伪NAS”程序。<br/>部署在：<br/>
>- 云服务器端(数据中转) 需有Php环境，需要Nginx或者Apache
>- 受控下载端 
>- 控制端（目前只有安卓App）[APP下载地址](https://m3w.cn/__uni__b741338)
## 2，设计的初衷是什么？
手上有一个树莓派，不想让它继续吃灰，所以想让它为我的生活做出点贡献。并且我并不想让它直接暴露在网络中，所以不准备采用内网穿透。
## 3，如何上手使用？
- 1）下载本仓库
- 2）修改host文件夹内conf.data.php文件内“用户名”和”密码“，默认用户名为admin,密码为123456
- 3）将host文件夹内所有文件上传到云服务器上，并在SSH内运行，命令如下
>- php Your file path/start.php start -d 
>- // 无-d表示调试模式，php部分可以参考[GatewayWorker](http://doc2.workerman.net/)
>- 理论上您无需修改任何关键文件即可运行
- 4）将download文件夹内文件传输到树莓派上
- 5）将app内文件下载至安卓手机并安装，登录
- 6）修改download文件夹下conf.json
> 您需要修改的文件内容为
>- api 修改为您的在线php API文件访问地址，如：https://XXXX/api.php
> - 此处user配置下，token必须和云端token相同
- 7）启动受控客户端文件
> python Your file path/wdownload.py
## 4，现在已实现的功能有哪些？
-1）APP端“添加任务”，“删除任务”，“登录”
-2）受控端同步下载,但无法设置任务完成
## 5，APP演示
![APP演示动图](https://github.com/Pidbid/W-Download/blob/master/dist/show.gif)
## 6，下一步准备做什么？
- 受控端同步下载
- 下载的过程中返回下载进度
## 7，注意
写这个功能纯属个人爱好，代码目前存在着很大的优化空间，可能还存在着不可预知的BUG，但我会逐渐的完善和修复。如果您感兴趣，请点亮star👍，谢谢。最后您也可以移步本人博客：[歪克士www.wicos.me](https://www.wicos.me)查看更多内容。