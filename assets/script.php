<?php 
session_start();
print_r($_SESSION);die;
echo "
var heyy=".$_SESSION['tour_data'].";
 var stylet=document.getElementsByTagName('td');
 j=stylet.length;
 for(x=0;x<j;x++)
  {
		stylet[x].style.borderColor ='white';
		stylet[x].style.borderWidth ='initial';
  }
var bodystyle = document.getElementById('body1');
bodystyle.style.backgroundImage='url(../assets/blue.jpg)';
bodystyle.style.backgroundSize='cover';
alert(heyy.round[0][0].team_count);
var length1 = heyy.round.length;
var here1 = [document.getElementsByClassName('player1'),document.getElementsByClassName('player2'),document.getElementsByClassName('player3')]
//console.log(here1);
var here2 = [document.getElementsByClassName('splayer1')];
//console.log(here2);
var here3 = [document.getElementById('tplayer1')];
var here4 = [document.getElementById('fplayer1')];
var here5 = [document.getElementById('fiplayer1')];
var here6 = [document.getElementById('winner')];
var o=0;
for(i=0;i<length1;i++)
{
length2=heyy.round[i].length*2;
//console.log(length2);
 if(i==0)
 {
 if(length2>3)
 {
	for(k=4;k<length2;k++)
	 {
	 here1.push(document.getElementsByClassName('player'+k));
 }
 }
for(m=0,j=0;m<length2/2;m++)
 {
	 here1[j][0].innerHTML=heyy.round[i][m].team1;
	 here1[j][0].style.color='#4dfffb';
     here1[j][0].style.fontSize= 'x-large';
     j++;
     if(heyy.round[i][m].team2!=='0')
     {
	 here1[j][0].innerHTML=heyy.round[i][m].team2;
	 here1[j][0].style.color='#4dfffb';
     here1[j][0].style.fontSize= 'x-large';
	 j++;
	 }
	}
}
else if(i==1)
{
	if(length2>1)
 {
	for(k=2;k<=length2;k++)
	 {
	 here2.push(document.getElementsByClassName('splayer'+k));
 }
 }
 var length3=here2.length;
 length3=length3-1;
for(m=0,j=length3;m<length2/2;m++)
 {  //console.log('here the value  '+i+ ' and the value of m is '+m);
 	if(heyy.round[i][m].team1)
 	{
 	here2[j][0].innerHTML=heyy.round[i][m].team1;
	 here2[j][0].style.color='#4dfffb';
     here2[j][0].style.fontSize= 'x-large';
     j--;
 	}
 	else{
	 here2[j][0].innerHTML='winner off round'+heyy.round[i][m].team1_parent;
	 here2[j][0].style.color='#4dfffb';
    here2[j][0].style.fontSize= 'x-large';
     j--;}
     if(heyy.round[i][m].team2)
     {
     here2[j][0].innerHTML=heyy.round[i][m].team2;
	 here2[j][0].style.color='#4dfffb';
     here2[j][0].style.fontSize= 'x-large';
	 j--;
	}
 else{
	 here2[j][0].innerHTML='winner off round'+heyy.round[i][m].team2_parent;
	 here2[j][0].style.color='#4dfffb';
     here2[j][0].style.fontSize= 'x-large';
	 j--;
	 }
	 }
}
else if(i==2)
{
	if(length2>1)
 {
	for(k=2;k<=length2;k++)
	 {
	 here3.push(document.getElementById('tplayer'+k));
 }
 }
for(m=0,j=0;m<length2/2;m++)
 {
	 here3[j].innerHTML=heyy.round[i][m].team1_parent;
	 here3[j].style.color='#4dfffb';
     here3[j].style.fontSize= 'x-large';
     j++;
	 here3[j].innerHTML=heyy.round[i][m].team2_parent;
	 here3[j].style.color='#4dfffb';
     here3[j].style.fontSize= 'x-large';
	 j++;
	 }
}
else if(i==3)
{ 
	if(length2>1)
 {
	for(k=2;k<=length2;k++)
	 {
	 here4.push(document.getElementById('fplayer'+k));
 }
 }
 var length4=here4.length;
 length4=length4-1;
for(m=0,j=length4;m<length2/2;m++)
 {
	 here4[j].innerHTML=heyy.round[i][m].team1_parent;
	 here4[j].style.color='#4dfffb';
     here4[j].style.fontSize= 'x-large';
     j--;
	 here4[j].innerHTML=heyy.round[i][m].team2_parent;
	 here4[j].style.color='#4dfffb';
     here4[j].style.fontSize= 'x-large';
	 j--;
	 }
}
else if(i==4)
{
		if(length2>1)
 {
	for(k=2;k<=length2/2;k++)
	 {
	 here5.push(document.getElementById('fiplayer'+k));
 }
 }
for(m=0,j=0;m<length2/2;m++)
 {
	 here5[j].innerHTML=heyy.round[i][m].team1_parent;
	 here5[j].style.color='#4dfffb';
     here5[j].style.fontSize= 'x-large';
     j++;
	 }
}

 var timetab = [];
 for (z=1;z<=32;z++)
 {
	 timetab.push(document.getElementById('time'+z));
 }
 //console.log(heyy.round[0][0].start_time);
 var myDate=heyy.round[0][0].start_time;
myDate=myDate.split('-');
var newDate=myDate[1]+'/'+myDate[2]+'/'+myDate[0];
//console.log(newDate);
 for(j=0;j<length2/2;j++)
 { 
	 if(heyy.round[i][j].start_time)
	 { 
	 	if(heyy.round[i][j].team2||heyy.round[i][j].team2_parent)
{     
	 	var month=heyy.round[i][j].start_date;
	 	var hour=heyy.round[i][j].time;
	 	var court=heyy.round[i][j].court;
	 	//console.log(timetab[o]);
	
}
	 }
 else
 {	 timetab[o].innerHTML='xpm yb/z  Courtn';
}
if(timetab[o])
{    
	 timetab[o].innerHTML=hour+' '+month+' '+court;
	 timetab[o].style.color='#14ff3c';
     timetab[o].style.fontSize= 'x-large';
	 timetab[o].style.width= '100px';
}
	 o++;

	}

}
";



?>