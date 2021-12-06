### 1- Installation
````
composer require kasim.yilmaz/sftp-helper
````
<br>
<br>
<br>

### 2- Initialize

````php
$sftp_serfer = "ftp.ftp.com";
$port = "22";
$username = "username";
$password = "password";
$incomingFolder = "/ftp/folder";
sftp = new SftpHelper($ftp_server,$port, $username,$password,$incomingFolder);

````
<br>
<br>
<br>

### 3- Open Connection
````php
$sftp->open();
````
<br>
<br>
<br>

### 4 - Check is connection succesful

````php
if($sftp->status)
{
    echo "Connection Succesful";
}else
{
    throw new Exception("Connection Failed :( ");
};
````

### 5- Put a file

````php
$sftp->put($folder,$fileName,$fileContent);

````
<br>
<br>
<br>



### 6- Check is existing before creating  a folder

````php
$isExist = $sftp->isExist($path);
````

<br>
<br>
<br>

### 7- Create a folder

````php
$folderPath  ="/var/folder/folder";
$isCreated = $sftp->createFolder($folderFullPath);
````
<br>
<br>
<br>

### 8- Get file list of folder

````php
$folderPath  ="/var/folder/folder";
$arrayOfFileNameList = $sftp->GetAllFileList($folderPath);
````
<br>
<br>
<br>

### 9- Get File Contents via File List

````php
$folderPath  ="/var/folder/folder";
$isExist = $sftp->GetFileContents($fileList,$folderPath);
````

