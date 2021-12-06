<?php
/**
 * @author [Kasım YILMAZ]
 * @email [kasim.yilmaz@pts.net]
 * @create date 2021-11-17 13:51:58
 * @modify date 2021-11-17 13:51:58
 * @desc [description]
 */
class SftpHelper
{    
    /**
     * host
     *
     * @var string
     */
    protected $host;
    
    /**
     * port
     *
     * @var string
     */
    protected $port;
    
    /**
     * userName
     *
     * @var string
     */
    protected $userName;
    
    /**
     * pwd
     *
     * @var string
     */
    protected $pwd;
    
    /**
     * connection
     *
     * @var string
     */
    public $sftpConnection;
    
    /**
     * status
     *
     * @var bool ///is connect or not
     */
    public $status=false;
    
    public $path;

    
    /**
     * __construct
     *
     * @param  string $host
     * @param  string $port
     * @param  string $userName
     * @param  string $pwd
     * @param  string $path
     * @return void
     */
    public function __construct($host, $port, $userName, $pwd, $path)
    {
        $this->host = $host;
        $this->port = $port;
        $this->userName = $userName;
        $this->pwd = $pwd;
        $this->path = $path;
    }

    
    /**
     * open
     *
     * @return void
     */
    public function open()
    {
        try {
            $conn_id = ssh2_connect($this->host, $this->port);
            if(!$conn_id)   throw new Exception("Connection fail");
            if(! ssh2_auth_password($conn_id, $this->userName, $this->pwd))  throw new Exception("Auth unsucces");
            $this->sftpConnection = ssh2_sftp($conn_id);
            if(!$this->sftpConnection) throw new Exception("Connection attempt did nor succes");
            $this->status=true;
        } catch (Exception $e) {
            //$this->status=false;
            throw new Exception($e->getMessage().":".$this->host);
        }
           
    }
    
    /**
     * put
     *
     * @param  string $path
     * @param  string $fileName
     * @param  string $content
     * @return bool
     */
    public function put($path,$fileName,$content)
    {
        try {
            $sftp_fd = intval($this->sftpConnection);   
            if (!$remote = fopen("ssh2.sftp://".$sftp_fd.$path.$fileName, 'w')) {
                //echo "Failed to open remote file: $filen";
                throw new Exception("Failed to open remote file: $fileName");
            }

            if (fwrite($remote, $content) === FALSE) {
                throw new Exception("Failed to write to local file: $fileName");
            }
            fclose($remote);
            return true;
        } catch (Exception $th) {
            //throw $th;
            return false;
        }

    }    
    /**
     * isExist
     *
     * @param  string $folderPath
     * @return bool
     */
    public function isExist($folderPath):bool
    {
        $sftp_fd = intval($this->sftpConnection);
        $isExist = file_exists("ssh2.sftp://".$sftp_fd.$folderPath);
        return $isExist;
    }    
    /**
     * createFolder
     *
     * @param  string $folderPath
     * @return bool
     */
    public function createFolder($folderPath)
    {
        try 
        {
            $isCreated = ssh2_sftp_mkdir($this->sftpConnection,$folderPath);
            return $isCreated;
        } catch (\Throwable $th) {
            //echo $th->getMessage();
            return false;
        }
    }    
    /**
     * move
     *
     * @param  string $fromPath
     * @param  string $targetPath
     * @return bool
     */
    public function move($fromPath,$targetPath):bool
    {
        try {
            $isMOved = ssh2_sftp_rename($this->sftpConnection, $fromPath, $targetPath);
            return $isMOved;
        } catch (\Throwable $th) {
             //echo $th->getMessage()
            return false;
        }
    }    
    /**
     * GetAllFileList
     *
     * @param  string $folderPath
     * @return array
     */
    public function GetAllFileList($folderPath)
    {
        $sftp_fd = intval($this->sftpConnection); 
        $dir ="ssh2.sftp://".$sftp_fd.$folderPath;
        $handle = opendir($dir);
        $fileList = [];
        while (false != ($file = readdir($handle)))
        {
 	    
            if ($file != '.' && $file != '..') 
            {
               $fileList[] = $file;                
            }
        }
        return $fileList;
    }    
    /**
     * GetFileContents
     *
     * @param  mixed $fileNameList
     * @param  mixed $path
     * @return array
     */
    public function GetFileContents(array $fileNameList,$path)
    {
        $sftp_fd = intval($this->sftpConnection); 
        $fileContentList = [];
        $dir ="ssh2.sftp://".$sftp_fd.$path;
        foreach ($fileNameList as $key => $fileName) {
            $fileContentList[]=file_get_contents($dir."/".$fileName);            
            
        }
        return $fileContentList;
    }
   
}
