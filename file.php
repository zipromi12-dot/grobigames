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
echo'<a href="/road'.$ref.'" class="button_red_a">Сражаться</a>';
echo'<div style="padding-top: 5px;"></div>';
echo'<div class="line_1_m"></div>';
echo'<div class="body_list">';
echo'<form method="post" action="/auth?act=login">';
echo'<input class="input_form" type="text" name="nick" placeholder="Имя героя" maxlength="25"/><br/>';
echo'<input class="input_form" type="password" name="password" autocomplete="off" placeholder="Пароль входа" maxlength="25"/><br/>';
echo'<div style="padding-top: 5px;"></div>';
echo'<input  class="button_green_i" name="submit" type="submit"  value=" Вход "/>';
echo'<div style="padding-top: 3px;"></div>';

echo'<a href="/restorn" class="button_red_a">Забыли пароль?</a>';
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
                                        
echo'<img src="/style/images/body/text.png" class="img"/>';
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
echo'<li><a href="/podarok_act?act=give"><img src="/style/images/body/gift.png" alt=""/> Подарок <span class="white">(Получить: <span class="yellow">'.$img_podarok.''.$user['podarok'].'</span>)</span></a></li>';
echo'<div class="line_1"></div>';
}else{
echo'<li><a href="/"><img src="/style/images/body/gift.png" alt=""/> Подарок <span class="white">(<img src="/style/images/body/error.png" alt=""/>Подарков нет)</span></a></li>';	
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
echo'</div><a href="/boss"><div class="menu-wi bit"><span class="title-wi"><font color="#FA8072">Боссы</font></span><font color="#A9A9A9">Сражение против монстров</font><div align="left"><font color="tomato">Боев доступно: '.$battle_o.' </font></div></div></a>';
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
echo'<a href="/select_location"><div class="menu-wi fer"><span class="title-wi"><font color="#6B8E23">Охота</font></span><font color="#A9A9A9">Сражайся с нечистью</font><div align="left"><font color="tomato">Доступны новые сражения</a></font></div></font></div></a>';
}else{
echo'<a href="/select_location"><div class="menu-wi fer"><span class="title-wi"><font color="#6B8E23">Охота</font></span><font color="#A9A9A9">Сражайся с нечистью</font></div></a>';	
}
}else{
echo'<a href="/hunting_battle"><div class="menu-wi fer"><span class="title-wi"><font color="#6B8E23">Охота</font></span><font color="#A9A9A9">Сражайся с нечистью</font> бой</a><div align="left"><font color="tomato"> У вас открытый /font></div></a></div>';

}
echo'<div class="block2 grab">';

		echo'<div class="onl"><a href="/duel"><div class="menu-wai"><br><font color="#777777">Дуели</font></div></a></div>
		<div class="onl"><a href="/zamki"><div class="menu-wai"><br><?=$bo;?><font color="#777777">Замки</font></div></a></div>
		<div class="onl"><a href="/reid"><div class="menu-wai"><br><font color="#777777">Рейды</font></div></a></div></div></div>';
		
		
		
		
echo'<div class="block2 grab">';		
		
		echo'<div class="onl"><a href="/coliseum"><div class="menu-wai"><br><font color="#777777">Колизей</font></div></a></div>
		<div class="onl"><a href="/towers"><div class="menu-wai"><br><?=$bo;?><font color="#777777">Башни</font></div></a></div>
		<div class="onl"><a href="/pets_duel"><div class="menu-wai"><br><font color="#777777">Бои питомцев</font></div></a></div></div></div>';
		
		
		
		
		echo'<div class="block2 grab">';		
		
		echo'<div class="onl"><a href="/tournament_users"><div class="menu-wai"><br>Турнир игроков</div></a></div>
		<div class="onl"><a href="/tournament_clan"><div class="menu-wai"><br><?=$bo;?><font color="#777777">Турнир кланов</font></div></a></div>
		<div class="onl"><a href="/clan"><div class="menu-wai"><br><font color="#777777">Кланы</font></div></a></div></div></div>';
		
		
		
		
		echo'<div class="block2 grab">';		
		
		echo'<div class="onl"><a href="/trade_shop"><div class="menu-wai"><br>Торговая лавка</div></a></div>
		<div class="onl"><a href="/blacksmith"><div class="menu-wai"><br><?=$bo;?>Кузнец</font></div></a></div>
		<div class="onl"><a href="/exchanger"><div class="menu-wai"><br><font color="#777777">Обменник</font></div></a></div></div></div>';
		
		
		
		
		
		
		echo'<div class="block2 grab">';		
		
		echo'<div class="onl"><a href="/daily_tasks"><div class="menu-wai"><br>Задания</div></a></div>
		<div class="onl"><a href="/rating"><div class="menu-wai"><br><?=$bo;?>Зал славы</font></div></a></div>
		<div class="onl"><a href="/payment"><div class="menu-wai">Покупка <br>золот</div></a></div></div></div>';
		
}
}

echo'</div>';
echo'</div>';
require_once H.'system/footer.php';
?>	