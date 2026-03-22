<?php
require_once 'system/system.php';
header('Content-Type:text/html; charset=utf-8');
if($user['start'] == '0'){
$head = 'Обучение';
}else{
$head = 'Главная';
}
$text_location = '<img src="/style/images/body/text.png" class="text_logo"/>';
require_once H.'system/head.php';
global $user;
echo'<div class="page">';
#-Если не авторизованы-#
if(!isset($user['id'])){
#-Реф ссылка или нет-#
if(isset($_GET['ref'])){
$ref = "?ref=$_GET[ref]";
}
echo'<img src="/style/images/body/text.png" class="img"/>';
echo'<center>';
echo'<div class="line_3"></div>';
echo'<div class="body_list">';
echo'<div style="padding-top: 3px;"></div>';
#-Кол-во игроков-#
$sel_users_a = $pdo->query("SELECT COUNT(*) FROM `users`");
$users_all = $sel_users_a->fetch(PDO::FETCH_LAZY);
#-Кол-во игроков света-#
$sel_users_s = $pdo->query("SELECT COUNT(*) FROM `users` WHERE `storona` = 1");
$users_shine = $sel_users_s->fetch(PDO::FETCH_LAZY);
$u_s = round(($users_shine[0]/$users_all[0])*100, 0);
#-Кол-во игроков тьмы-#
$sel_users_d = $pdo->query("SELECT COUNT(*) FROM `users` WHERE `storona` = 2");
$users_dark = $sel_users_d->fetch(PDO::FETCH_LAZY);
$u_d = round(($users_dark[0]/$users_all[0])*100, 0);
echo'<span class="whit">Баланс сил:<br/> <img src="/style/images/user/shine.png" alt=""/>'.$u_s.'% vs <img src="/style/images/user/dark.png" alt=""/>'.$u_d.'%</span>';
echo'<div style="padding-top: 4px;"></div>';
echo'</div>';
echo'<div class="line_3"></div>';
echo'<div style="padding-top: 5px;"></div>';
echo'<a href="/road'.$ref.'" class="btn">Сражаться</a>';
echo'<div style="padding-top: 5px;"></div>';
echo'<div class="line_1_m"></div>';
echo'<div class="body_list">';
echo'<form method="post" action="/auth?act=login">';
echo'<input class="input_form" type="text" name="nick" placeholder="Имя героя" maxlength="25"/><br/>';
echo'<input class="input_form" type="password" name="password" autocomplete="off" placeholder="Введите пароль" maxlength="25"/><br/>';
echo'<div style="padding-top: 5px;"></div>';
echo'<input  class="button_green_i" name="submit" type="submit"  value=" Вход "/>';
echo'<div style="padding-top: 3px;"></div>';

echo'<a href="/restorn" class="btn3">Забыли пароль?</a>';
echo'<div style="padding-top: 5px;"></div>';
echo'</form>';
echo'</div>';
echo'</center>';
echo'<div class="line_1_m"></div>';
echo'<div style="padding: 5px;">';
echo'<img src="/style/images/body/traing.png" alt="" style="margin-right:2px;"/><span class="yellow"><b>Война Героев</b></span><span class="whit"> - увлекательная мобильная игра которая не оставит равнодушным того кто любит настоящие mmorpg сражения.</span>';
echo'</div>';
echo'<div class="line_4"></div>';


#-Если авторизованы-#
}else{
	
if($user['start'] == 0){ //Если еще не проходили обучение
echo'<img src="/style/images/start/battle.jpg" class="img" alt=""/>';
echo'<div class="body_list">';
echo'<div class="line_3"></div>';
echo'<div class="menulist">';
echo'<img src="/style/images/start/grey.png" class="menulitl_img" width="30" height="30" alt=""/><div class="menulitl_block"><span class="menulitl_name">Грей:</span> <div class="menulitl_param">Теперь ты можешь сражаться за ту сторону которую выбрал. Но какое сражение без оружия?</div></div>';
echo'</div>';
echo'<div style="padding-top: 3px;"></div>';
echo'</div>';
echo'<div class="line_3"></div>';
echo'<div class="weapon">';
echo'<div class="img_weapon"><img src="/style/images/weapon/arm/1.png" class="weapon_1"  alt=""></div>';
echo'<div class="weapon_setting">';
echo'<span style="color: #bfbfbf;"><img src="/style/images/quality/1.png" alt=""/><b>Топор Рыцаря</b></span><br/>';
echo'<div class="weapon_param"><img src="/style/images/user/sila.png" alt=""/>250 <img src="/style/images/user/zashita.png" alt=""/>250 <img src="/style/images/user/health.png" alt=""/>250<br/>';
echo'</div></div>';
echo'</div>';
echo'<div style="padding-top: 10px;"></div>';
echo'<a href="/campaign?act=battle" class="button_green_a">Взять оружие и в бой <img src="/style/images/body/left_mini.gif" alt=""/></a>';
echo'<div style="padding-top: 3px;"></div>';

}else{
	
#-Ежедневная награда-#
if($user['every_num'] != $user['every_statys']){
echo'<div class="page">';
echo"<div style='padding: 5px;'><center>";
echo'<a href="/everyday" class="button_green_a"><img src="/style/images/body/gift.png" alt=""/>Получить награду</a>';
echo'</center></div>';
echo'</div>';
}
                                        

echo'<div class="body_list">';
echo'<div class="menulist">';
echo'<div class="line_3"></div>';

#-Админка-#
if($user['prava'] == 1){
echo'<li><a href="/admin"><img src="/style/images/chat/admin.png" alt=""/> Админка</a></li>';
echo'<div class="line_1"></div>';
}
#-Если есть права модератора или админа-#
if($user['prava'] == 1 or $user['prava'] == 2 or $user['prava'] == 3){
#-Выборка сколько сообщение не прочтено в беседе-#
$sel_chat_m = $pdo->prepare("SELECT COUNT(*) FROM `chat_moderator` WHERE `id` > :chat_moder");
$sel_chat_m->execute(array(':chat_moder' => $user['chat_moder']));
$amount_m = $sel_chat_m->fetch(PDO::FETCH_LAZY);
if($amount_m[0] > 0){
echo'<li><a href="/chat_moderator"><img src="/style/images/chat/moder.png"> Беседа <span class="green">(+)</span></a></li>';
}else{
echo'<li><a href="/chat_moderator"><img src="/style/images/chat/moder.png"> Беседа</a></li>';
}
echo'<div class="line_1"></div>';
}

#-Подарок-#
if($user['podarok'] != 0){
#-Ключи-#
if($user['type_podarok'] == 1){
$img_podarok = '<img src="/style/images/body/key.png" alt=""/>';
}
#-Серебро-#
if($user['type_podarok'] == 2){
$img_podarok = '<img src="/style/images/many/silver.png" alt=""/>';
}
#-Золото-#
if($user['type_podarok'] == 3){
$img_podarok = '<img src="/style/images/many/gold.png" alt=""/>';
}
#-Кристаллы-#
if($user['type_podarok'] == 4){
$img_podarok = '<img src="/style/images/many/crystal.png" alt=""/>';
}
echo'<center><a href="/podarok_act?act=give"><img src="/style/images/body/gift.png" alt=""/> Подарок <span class="white">(Получить: <span class="yellow">'.$img_podarok.''.$user['podarok'].'</span>)</span></a></center>';
echo'<div class="line_1"></div>';
}
echo'<div class="line_1"></div>';

/*
#-Хэллоуин-#
$halloween_time = $user['celebration_time']-time();
echo'<li><a href="/halloween_battle"><img src="/style/images/body/helloween.png" alt=""/> <span class="purple">Хэллоуин '.($user['celebration_time'] != 0 ? "(".(int)($halloween_time/3600).":".(int)($halloween_time/60%60).")" : "").'</span></a></li>';
echo'<div class="line_1"></div>';
*/
echo'<div class="mini-line"></div>';
echo'<div class="line_1_v"></div></div></div></div></div></div></div></div>';

#-БОССЫ-#
if($user['level'] >= 5){
#-В бою или нет-#
$sel_boss_u = $pdo->prepare("SELECT * FROM `boss_users` WHERE `user_id` = :user_id");
$sel_boss_u ->execute(array(':user_id' => $user['id']));
if($sel_boss_u -> rowCount() == 0){
#-Проверяем какое количество боссов на отдыхе-#
$sel_amount_t = $pdo->prepare("SELECT COUNT(DISTINCT(boss_id)) FROM `boss_time` WHERE `user_id` = :user_id AND `type` = 2");
$sel_amount_t->execute(array(':user_id' => $user['id']));
$amount_t = $sel_amount_t->fetch(PDO::FETCH_LAZY);
#-Проверяем сколько всего Боссов для нашего лвл-#
$sel_amount_b = $pdo->prepare("SELECT COUNT(*) FROM `boss` WHERE `level` <= :user_lvl AND `type` != 4"); //type != 4
$sel_amount_b->execute(array(':user_lvl' => $user['level']));
$amount_b = $sel_amount_b->fetch(PDO::FETCH_LAZY);
$battle_o = $amount_b[0] - $amount_t[0];
echo'</div><a href="/boss"><div class="menu-wi bit"><span class="title-wi"><font color="#FA8072">Боссы</font></span><font color="#A9A9A9">Сражение против монстров</font><div align="left"><font color="lime">Боев доступно: '.$battle_o.'</font></div></div></a>';
}else{
echo'</div><a href="/boss_battle"><div class="menu-wi bit"><span class="title-wi"><font color="#FA8072">Боссы</font></span><font color="#A9A9A9">Сражение против монстров</font><div align="left"><font color="tomato">У вас открытый бой</font></div></div></a>';	
}
}else{
echo'</div><a href=""><div class="menu-wi bit"><span class="title-wi"><font color="#FA8072">Боссы</font></span><font color="#A9A9A9">Сражение против монстров</font><div align="left"><font color="lime">Требуется: <img src="/style/images/user/level.png" alt=""/> 5 лвл</font></div></div></div></div></div></div></div></div></div></a>';
	
}

#-ОХОТА-#
#-Проверяем в бою мы или нет-#
$sel_hunting_b = $pdo->prepare("SELECT * FROM `hunting_battle` WHERE `user_id` = :user_id AND `statys` = :statys");
$sel_hunting_b->execute(array(':user_id' => $user['id'], ':statys' => '0'));
if($sel_hunting_b-> rowCount() == 0){
#-Проверяем есть группа или нет-#
$sel_hunting_t = $pdo->prepare("SELECT COUNT(*) FROM `hunting_time` WHERE `user_id` = :user_id");
$sel_hunting_t->execute(array(':user_id' => $user['id']));
$hunting_t = $sel_hunting_t->fetch(PDO::FETCH_LAZY);
$sel_hunting_b = $pdo->prepare("SELECT COUNT(*) FROM `hunting` WHERE `level` <= :user_lvl");
$sel_hunting_b->execute(array(':user_lvl' => $user['level']));
$hunting_b = $sel_hunting_b->fetch(PDO::FETCH_LAZY);
if($hunting_t[0] != $hunting_b[0]){
echo'<a href="/select_location"><div class="menu-wi fer"><span class="title-wi"><font color="#6B8E23">Охота</font></span><font color="#A9A9A9">Сражайся с нечистью</font><div align="left"><font color="lime">Доступны новые сражения</font></div></font></div></a>';
}else{
echo'<a href="/select_location"><div class="menu-wi fer"><span class="title-wi"><font color="#6B8E23">Охота</font></span><font color="#A9A9A9">Сражайся с нечистью</font></div></a>';	
}
}else{
echo'<a href="/hunting_battle"><div class="menu-wi fer"><span class="title-wi"><font color="#6B8E23">Охота</font></span><font color="#A9A9A9">Сражайся с нечистью</font></a><div align="left" style="vertical-align: -40px;"><font color="tomato">У вас открытый бой</font></div></a> 
</a></div>';

}
echo'<div class="block2 grab">';
if($user['level'] >= 5){
$sel_duel = $pdo->prepare("SELECT * FROM `duel_battle` WHERE `user_id` = :user_id");
$sel_duel->execute(array(':user_id' => $user['id']));
if($sel_duel-> rowCount() == 0){
#-Проверяем онлайн дуэли-#
$sel_duel_on = $pdo->prepare("SELECT * FROM `duel_online` WHERE (`user_id` = :user_id OR `ank_id` = :user_id) AND `statys` != 0");
$sel_duel_on->execute(array(':user_id' => $user['id']));
if($sel_duel_on-> rowCount() == 0){
$user_level = floor($user['level']/2);
$battle_d = $user_level - $user['duel_b'];
echo'<div class="onl"><a href="/duel"><div class="menu-wai"><b>Дуэли </b><span style="font-size: 13px;color: #666666;"><br>Боев: <img src="/style/images/body/league.png">'.$battle_d.')</span></div></a></div>';
}else{
echo'<div class="onl"><a href="/duel_online"><div class="menu-wai"><b> Дуэли </b><span class="red">(+)</span></div></a></div>';	
}
}else{
echo'<div class="onl"><a href="/duel_battle"><div class="menu-wai"><b> Дуэли </b><span class="red">(+)</span></div></a></div>';
}
}else{
echo'<div class="onl"><a href="/duel"><div class="menu-wai"><span class="white">Дуэли<br> С <img src="/style/images/user/level.png" alt=""/>5 лвл</span></div></a></div>';
}	

#-ЗАМКИ-#
if($user['level'] >= 10){
$sel_zamki = $pdo->query("SELECT * FROM `zamki`");
if($sel_zamki->rowCount() != 0){
$zamki = $sel_zamki-> fetch(PDO::FETCH_LAZY);
$zamki_ost = $zamki['time'] - time();
$zamki_time = "<span class='white'>(".($zamki_ost/60%60).":".($zamki_ost%60).")</span>";
}
echo"<div class='onl'><a href='/zamki'><div class='menu-wai'><b> Замки</b><br>$zamki_time</div></a></div>";
}else{
echo"<div class='onl'><a href='/zamki'><div class='menu-wai'><span class='white'>Замки<br>С <img src='/style/images/user/level.png' alt=''/>10 лвл</span></div></a></div>";
}
		
#-РЕЙД-#
echo'<div class="line_1"></div>';
if($user['level'] >= 20){
#-Время до сражения-#
$sel_reid_t = $pdo->query("SELECT * FROM `reid_boss`");
if($sel_reid_t-> rowCount() != 0){
$reid_t = $sel_reid_t-> fetch(PDO::FETCH_LAZY);
$reid_ost = $reid_t['time']-time();
$reid_time = "<span class='white'>(".timers_mini($reid_ost).")</span>";
}
echo'<div class="onl"><a href="/reid"><div class="menu-wai"><b> Рейды:</b><br>'.$reid_time.'</div></a></div>';
}else{
echo'<div class="onl"><a href="/reid"><div class="menu-wai"> <span class="white">Рейды<br>С <img src="/style/images/user/level.png" alt=""/>20 лвл</span></div></a></div>';
}

#-КОЛИЗЕЙ-#
echo'<div class="line_1"></div>';
if($user['level'] >= 13){
echo'<div class="onl"><a href="/coliseum"><div class="menu-wai"><b>Колизей</b></div></a></div>';
}else{
echo'<div class="onl"><a href="/coliseum"><div class="menu-wai"><span class="white">Колизей<br> С <img src="/style/images/user/level.png" alt=""/>13 лвл</span></div></a></div>';
}


#-БАШНИ-#
if($user['level'] >= 25){
echo'<div class="onl"><a href="/towers"><div class="menu-wai"><span class="white"><b> Башни</b></div></a></div>';
}else{
echo'<div class="onl"><a href="/towers"><div class="menu-wai"><span class="white">Башни<br> С <img src="/style/images/user/level.png" alt=""/>25 лвл</span></div></a></div>';
}

		
		
		echo'<div class="onl"><a href="/clan"><div class="menu-wai"><b>Кланы</b></font></div></a></div></div></div>';
		
		
		
		
		echo'<div class="block2 grab">';		
		
		echo'<div class="onl"><a href="/tournament_users"><div class="menu-wai"><b>Турнир<br> игроков</b></div></a></div>
		<div class="onl"><a href="/tournament_clan"><div class="menu-wai"><?=$bo;?><b>Турнир<br> кланов</b></font></div></a></div>
		<div class="onl"><a href="/pets_duel"><div class="menu-wai"><b>Бои<br> питомцев</b></font></div></a></div></div></div>';
		
		
		
		echo'<div class="block2 grab">';		
		
		echo'<div class="onl"><a href="/trade_shop"><div class="menu-wai"><b>Магазин</b></div></a></div>
		<div class="onl"><a href="/blacksmith"><div class="menu-wai"><?=$bo;?><b>Кузнец</b></font></div></a></div>
		<div class="onl"><a href="/exchanger"><div class="menu-wai"><b>Обменник</b></font></div></a></div></div></div>';
		
		
		
		
		
		
	        echo'<div class="block2 grab">';		
		
		
		
		
		
		$sel_tasks_n = $pdo->prepare("SELECT COUNT(*) FROM `daily_tasks` WHERE `user_id` = :user_id");
$sel_tasks_n->execute(array(':user_id' => $user['id']));
$amount_n = $sel_tasks_n->fetch(PDO::FETCH_LAZY);
#-Выполненые задания-#
$sel_tasks_v = $pdo->prepare("SELECT COUNT(*) FROM `daily_tasks` WHERE `user_id` = :user_id AND `statys` = 1");
$sel_tasks_v->execute(array(':user_id' => $user['id']));
$amount_v = $sel_tasks_v->fetch(PDO::FETCH_LAZY);
echo'<div class="onl"><a href="/daily_tasks"><div class="menu-wai"><b>Задания<br></b> <span class="white">('.$amount_v[0].'/'.$amount_n[0].')</span></a><br></div></a></div>
		
		
		
		

		
		<div class="onl"><a href="/rating"><div class="menu-wai"><?=$bo;?><b>Зал славы</b><br><br></font></div></a></div>
		<div class="onl"><a href="/payment"><div class="menu-wai"><b>Покупка<br>золота</b></div></a></div></div></div>';
		
}
}

echo'</div>';
echo'</div>';
require_once H.'system/footer.php';
?>	