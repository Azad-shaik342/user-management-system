<?php
declare(strict_types=1);
require_once __DIR__.'/config.php';
function db(): PDO {static $pdo=null; if($pdo===null){$pdo=new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8mb4',DB_USER,DB_PASS,[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC,PDO::ATTR_EMULATE_PREPARES=>false]);} return $pdo;}
function url(string $path=''):string{return APP_BASE.'/'.ltrim($path,'/');}
function e(?string $value):string{return htmlspecialchars($value??'',ENT_QUOTES|ENT_SUBSTITUTE,'UTF-8');}
function redirect(string $path):never{header('Location: '.url($path));exit;}
function csrf_token():string{if(empty($_SESSION['csrf']))$_SESSION['csrf']=bin2hex(random_bytes(32));return $_SESSION['csrf'];}
function csrf_field():string{return '<input type="hidden" name="csrf" value="'.e(csrf_token()).'">';}
function check_csrf():void{if(!hash_equals(csrf_token(),(string)($_POST['csrf']??''))){http_response_code(403);exit('Invalid request token. Go back, refresh and try again.');}}
function flash(string $type,string $msg):void{$_SESSION['flash']=['type'=>$type,'message'=>$msg];}
function current_user():?array{if(empty($_SESSION['uid']))return null;$q=db()->prepare('SELECT u.id,u.name,u.email,u.avatar,u.role_id,r.name AS role FROM users u JOIN roles r ON r.id=u.role_id WHERE u.id=?');$q->execute([$_SESSION['uid']]);$user=$q->fetch();if(!$user){unset($_SESSION['uid']);return null;}return $user;}
function require_login():array{$u=current_user();if(!$u)redirect('login.php');return $u;}
function require_admin():array{$u=require_login();if($u['role']!=='admin'){http_response_code(403);exit('403 Forbidden — administrator access required.');}return $u;}
function valid_name(string $s):bool{return mb_strlen($s)>=2 && mb_strlen($s)<=100;}
function valid_password(string $s):bool{return strlen($s)>=8 && strlen($s)<=72;}
function avatar_upload(string $field,?string $old=null):?string{
 if(!isset($_FILES[$field]) || $_FILES[$field]['error']===UPLOAD_ERR_NO_FILE)return $old;
 $f=$_FILES[$field];if($f['error']!==UPLOAD_ERR_OK)throw new RuntimeException('Upload failed.');
 if($f['size']>2*1024*1024)throw new RuntimeException('Image must be 2 MB or smaller.');
 if(!is_uploaded_file($f['tmp_name']))throw new RuntimeException('Invalid upload.');
 $mime=(new finfo(FILEINFO_MIME_TYPE))->file($f['tmp_name']);
 $allowed=['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp'];
 if(!isset($allowed[$mime]) || @getimagesize($f['tmp_name'])===false)throw new RuntimeException('Upload a valid JPG, PNG, or WebP image.');
 $name=bin2hex(random_bytes(20)).'.'.$allowed[$mime];
 $dest=__DIR__.'/../uploads/'.$name;
 if(!move_uploaded_file($f['tmp_name'],$dest))throw new RuntimeException('Unable to save image.');
 if($old && preg_match('/^[a-f0-9]{40}\.(jpg|png|webp)$/',$old))@unlink(__DIR__.'/../uploads/'.$old);
 return $name;
}
function page_start(string $title):void{$u=current_user();$f=$_SESSION['flash']??null;unset($_SESSION['flash']);?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="color-scheme" content="dark"><title><?=e($title)?> | UserHub</title><link rel="stylesheet" href="<?=e(url('assets/style.css'))?>"></head><body>
<header class="topbar"><a class="brand" href="<?=e(url('index.php'))?>"><span class="brand-icon">✦</span> UserHub</a><nav aria-label="Main navigation"><?php if($u):?><a href="<?=e(url('dashboard.php'))?>">Dashboard</a><a href="<?=e(url('profile.php'))?>">Profile</a><?php if($u['role']==='admin'):?><a href="<?=e(url('users.php'))?>">Manage Users</a><?php endif;?><form class="inline" method="post" action="<?=e(url('logout.php'))?>"><?=csrf_field()?><button class="nav-button" type="submit">Logout</button></form><?php else:?><a href="<?=e(url('login.php'))?>">Login</a><a class="nav-button" href="<?=e(url('register.php'))?>">Get started</a><?php endif;?></nav></header>
<main class="container"><?php if($f):?><div class="alert <?=e($f['type'])?>" role="status"><?=e($f['message'])?></div><?php endif;?>
<?php }
function page_end():void{?></main><footer>Built with PHP · MySQL · HTML · CSS · JavaScript</footer><script src="<?=e(url('assets/app.js'))?>" defer></script></body></html><?php }
