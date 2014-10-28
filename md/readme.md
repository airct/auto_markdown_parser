Vagrant 安裝和使用手冊
====================

#安裝 Vagrant
		
- Requirement
			
直接進入 http://www.vagrantup.com/downloads 頁面下載適合的 OS 版本

VirtualBox
	
*注意 如果是使用 win7 務必確認你的 command-line 是由系統管理者權限執行*
		
- 建立第一個 Vagrant 
		
**初始化 Vagrant**

基於管理方便，建立一個資料夾專門管理 Vagrant 虛擬機，以下所有指令都是再 D:/Vagrant 底下相關目錄下操作
			
`D:/Vagrant`
		
輸入以下指令
						
	$ mkdir centos-6.5
	$ cd centos-6.5
	$ vagrant init chef/centos-6.5

	A `Vagrantfile` has been placed in this directory. You are now
	ready to `vagrant up` your first virtual environment! Please read
	the comments in the Vagrantfile as well as documentation on
	`vagrantup.com` for more information on using Vagrant.

以上是建立一個目錄 centos-6.5 做為虛擬機目錄，並且產生 Vagrant 需要的 Vagrantfile 設定檔 
			
** 啟動 Vagrant **
		
現在第一次啟動我們的 Vagrant 輸入以下指令
				
`$ vagrant up `
				
啟動的過程中 Vagrant 會去檢查 Box 中是否有 chef/centos-6.5 這個 Box 存在，如果沒有會去 https://vagrantcloud.com/chef/centos-6.5 下載一個 vagrant box 回來，如果有就會直接啟動 vagrant 當然也可以預先下載 Box 只需要輸入以下指令
				
`$ vagrant box add chef/centos-7.0`
				
完成之後輸入就可以連進去我們的 vagrant 虛擬機
			
`$ vagrant ssh `
			
大功告成
			
			
**其他設定**
			
- synced_folder
					
vagrant 可以自動同步 Host 和 Guest 之間的檔案。只要將需要的檔案放入 project 下即可

*Example*				
	
	Host
		centos-6.5/
			  /Vagrantfile
			  /.vagrant
			  /put_it_here
			  /...

	guest 
		/vagrant/
			/put_it_here
			/..
			/..
		
**更改 synced_folder 設定**
				
`config.vm.synced_folder "../data", "/vagrant_data"`
				
第一個參數如果是相對目錄，則是以 project  為基準，第二次參數則是需要 guest 上的絕對路徑
					
`config.vm.synced_folder "D:\\AppServ\\www\\myprojectname", "/vagrant"`
	
			
**vagrant share**
			
 
			
**本機瀏覽**
				
`config.vm.network "private_network", ip: "192.168.33.11"`
				
Vagrantfile 中將本行註解掉，就可以 Host 中打上 192.168.33.11 瀏覽，就樣一般開網頁一樣瀏覽

https://vagrantcloud.com/ 有提供很多熱心人士製做的 box 可以使用
		
		
	
		
	
#OTHER	
	
	
**概念**
	
- BOX
包裝好的 Vagrant 虛擬機檔案 ， 不能被直接使用 ， 要先建立 project 然後 init 該 Box
	
例如 
		
	$ mkdir centos-6.5
	$ cd centos-6.5
	$ vagrant init chef/centos-6.5
	
這表示建一個 centos-6.5 資料夾 ， 並使用 chef/centos-6.5 這個 Box

預先下載 box 可以使用下方指令 

	$ vagrant box add chef/centos-7.0

box 本身可以被多個 project 使用 ， 並不會因為有多個 project 共用同一個 box 造成各 project 互相影響


Vagrant 資訊

查看 Vagrant 全域狀態

	$ vagrant global-status
	
	id       name    provider   state    directory                           
	-------------------------------------------------------------------------
	60f8c5e  default virtualbox running  D:/Vagrant/laravel                  
	82e6e49  default virtualbox poweroff d:/Vagrant/puppetlabs               
	1c92dac  default virtualbox running  d:/Vagrant/precise32                
	3668873  default virtualbox running  d:/Vagrant/centos-6.5               

	The above shows information about all known Vagrant environments
	on this machine. This data is cached and may not be completely
	up-to-date. To interact with any of the machines, you can go to
	that directory and run Vagrant, or you can use the ID directly
	with Vagrant commands from any directory. For example:
	"vagrant destroy 1a2b3c4d"

移除 Vagrant 虛擬機
			
	$ cd d:/Vagrant/puppetlabs
	$ vagrant destroy
	
	default: Are you sure you want to destroy the 'default' VM? [y/N] y
	==> default: Destroying VM and associated drives...
  
	$ vagrant global-status
	 
	id       name    provider   state    directory                           
	-------------------------------------------------------------------------
	60f8c5e  default virtualbox running  D:/Vagrant/laravel                  
	82e6e49  default virtualbox poweroff d:/Vagrant/puppetlabs                           
	3668873  default virtualbox running  d:/Vagrant/centos-6.5               

	The above shows information about all known Vagrant environments
	on this machine. This data is cached and may not be completely
	up-to-date. To interact with any of the machines, you can go to
	that directory and run Vagrant, or you can use the ID directly
	with Vagrant commands from any directory. For example:
	"vagrant destroy 1a2b3c4d"


##建立自己的 package box
	
	
- 安裝 CentOS 6.5
	
	依照一般方式安裝 cenots-6.5 網路卡 eth0 設定為 NAT， 系統啟動後 eth0 有可能未啟動，請檢查 `/etc/sysconfig/network-srcipts/ifcfg-eht0 `參數 onboot 是否為 yes

預設 root 的密碼使用 vagrant
		
- 安裝 VBoxGuestAdditions

掛載 VBoxGuestAdditions.iso
	
	# mount /dev/cdrom /media
	
	# yum install gcc -y
	# yum install perl -y
	# yum install kenerl-devel-2.6.32-431.el6.x86_64 -y
	
	# /media/VBoxGuestAdditions.run

- 設定 vagrant 預設帳號
	
	# adduser vagrant
	# passwd vagrant

密碼預設成 vagrant
	
建立登入免密碼驗證
		
	# cd /home/vagant
	 
	# mkdir .ssh
	# cd .ssh
	 
	# wget https://raw.githubusercontent.com/mitchellh/vagrant/master/keys/vagrant
	# wget https://raw.githubusercontent.com/mitchellh/vagrant/master/keys/vagrant.pub authorized_keys 
	
	# visudo
	
註解掉 `Defaults requiretty`
		
新增
	vagrant ALL=(ALL) NOPASSWD: ALL
		
以上安裝完畢就可以關機，準備產生自己的 Box
	
	# vagrant box add --base centos
	# mv package.box centos.box

	vagrant box add --name my/centos centos.box
	
	
- package 自己的 Box
 
	$ vagrant init hashicorp/precise32

啟動 vagrant machine
	$ vagrant up
	
連線進 vagrant machine
	$ vagrant ssh
	$ vagrant destroy
	
	vagrant init chef/centos-7.0
			
			