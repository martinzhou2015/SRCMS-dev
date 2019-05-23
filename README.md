# 欢迎使用SRCMS V3.0.0 Alpha 开发版 ![codebeat badge](https://codebeat.co/badges/67e58b6d-bc89-4f22-ba8f-7668a9c15c5a)

**SRCMS** 是一款安全应急响应与缺陷管理软件，致力于为大、中、小企业和组织提供“最敏捷、安全和美观的安全应急响应中心的建站解决方案，帮助企业建立属于自己的安全应急响应中心和体系”。有了SRCMS，您就可以像使用Discuz!搭建论坛一样容易，为您的企业建立安全应急响应中心平台。

> * **项目开发/维护**： Martin Zhou
> * **最后更新日期**：2017-11-03

## 效果预览
![image](https://s1.ax2x.com/2017/11/03/BIFzR.png)

## 授权说明
1. 任何人在未取得SRCMS开发者正式书面授权的情况下，不得将SRCMS项目源代码或二次开发过的源代码用作**商业**出售**用途**，否则将保留追究其法律责任的权利。 
2. 使用SRCMS搭建站点或二次开发时，请您在网页底部加注Powered By SRCMS的相关字样。如有特殊需求，请您及时与我们联系获取授权。


## 免责说明
SRCMS仅为建站软件，任何使用本建站程序搭建的网站其运营的内容所产生的法律纠纷与本项目以及本人无关。


## 运行配置说明
* 第一步：在本页面下载SRCMS源代码
* 第二步：将SRCMS释放至网站根目录，并在\Application\Common\Conf\db.php中编辑与数据库相关的配置
* 第三步：进入\Application\User\Controller\PostController.class.php，第63行修改报告提交提示邮箱信息。
* 至此所有初始配置已经全部完成，后台默认登陆账户/密码为: admin / admin ，请您登陆后在后台及时修改。


## 版本更新日志

##### 2017-11-03
* **新增** 全新的user.php前台设计，祝您快速搭建更好更稳定的SRC。
* **新增** 提交报告未审核时，用户可编辑报告
* **修复** 两处高危逻辑缺陷
* **修复** 前台登陆验证码设计缺陷


## 代码贡献提交说明
作为一款开源软件，如果各位有任何对本项目的功能改进建议和建设性的代码更改，欢迎通过pull request功能提交。


## 代码贡献榜
以下成员为SRCMS的开发版本做出了卓越的贡献，在此致以最诚挚的谢意。


## BUG提交说明
如果您在使用本框架或是二次开发中发现任何SRCMS的问题，欢迎迎通过Github的issue功能将问题反馈，Issue功能能够很好的帮助我们定位和跟踪问题的修复情况。 


## 友情链接
[腾讯xSRC](https://security.tencent.com/index.php/xsrc)

