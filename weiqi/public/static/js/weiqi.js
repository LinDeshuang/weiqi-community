var chess = 1;				//初始围棋颜色,1黑/0白
var absoluteChess = 2;		//是否只下一种棋子，1为黑棋，0为白棋，-1为标注死子，2为否
var chessStatusData = [];	//棋盘棋子位置状态记录
var curData = [];			//当前显示下棋数据记录
var allStepData = '';       //所有步数记录
var backoutData = [];       //撤销的棋子数据
//棋盘位置状态初始化
for(var i = 0 ; i < 19 ; i++){
	chessStatusData[i] = [];
	for(var j = 0 ; j < 19 ; j++){
		chessStatusData[i][j] = 2;
	}
}
//棋盘英文数组
var letterArr = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
//获取canvas棋盘
var wqBoard = document.getElementById('wqBoard');

//获取提子按钮
var oTakePiece = document.getElementById('takePiece');

//获取对弈按钮
var oPlayChess = document.getElementById('playChess');

//获取黑棋按钮
var oBlackChess = document.getElementById('blackChess');

//获取白棋按钮
var oWhiteChess = document.getElementById('whiteChess');

//获取撤销按钮
var oBackout = document.getElementById('backout');

//获取恢复按钮
// var oForward = document.getElementById('forward');
//获取canvas内容
var context = wqBoard.getContext('2d');
//设定棋盘尺寸跟body等宽
const CANVAS_WIDTH = document.getElementsByTagName('body')[0].offsetWidth;
wqBoard.width = CANVAS_WIDTH ;
wqBoard.height = CANVAS_WIDTH;
//棋盘的路数
const COL = 19;
//一个格子的宽度
const CELL = CANVAS_WIDTH / (COL+1);
//棋子半径
const RADIUS = Math.floor(CELL/2)-2;
window.onload = function(){
	//棋盘初始化
	wqBoardInit( wqBoard , COL , CELL , RADIUS , CANVAS_WIDTH );
	//如果curData不为空则初始化棋盘时把棋子数据显示出来
	if( sqlData != ''){
		for(var i = 0,len = sqlData.length ; i < len ; i++){
				oneStep(context , CELL , RADIUS , sqlData[i].x , sqlData[i].y , sqlData[i].status);
			}
	}

	//默认点击下棋
	wqBoard.onclick = function (e){
		var oEvent = e||event;
		oEvent.preventDefault();
	    var x = oEvent.offsetX-Math.floor( CELL / 3 );
    	var y = oEvent.offsetY-Math.floor( CELL / 3 );
	    var i = Math.floor( x / CELL );
		var j = Math.floor( y / CELL );
		onOneStep( context , CELL , RADIUS , x , y , chess );
		if(absoluteChess != 2){
			chess = absoluteChess;
		}else{
				
				chess = !chessStatusData[i][j];
		}
    	
	}
	//提子
	oTakePiece.onclick = function (){
		topAlert('请提走死子','info');
		wqBoard.onclick = function (e){
			var oEvent = e||event;
			oEvent.preventDefault();
	    	var x = oEvent.offsetX-Math.floor( CELL / 3 );
    		var y = oEvent.offsetY-Math.floor( CELL / 3 );
		    var i = Math.floor( x / CELL );
    		var j = Math.floor( y / CELL );
    		
    		if(chessStatusData[i][j] != 2 ){
    			clearChess( context , i , j , CELL , RADIUS );
    			chessStatusData[i][j] = 2;
    			curData.push({'x':i,'y':j,'status':-1});
    		}
    		
		}
		this.setAttribute('class','btn btn-secondary disable') ;
		this.setAttribute('aria-disabled','true');
		oPlayChess.setAttribute('class','btn btn-dark');
		oPlayChess.setAttribute('aria-disabled','false');
		oBlackChess.setAttribute('class','btn btn-dark');
		oBlackChess.setAttribute('aria-disabled','false');
		oWhiteChess.setAttribute('class','btn btn-dark');
		oWhiteChess.setAttribute('aria-disabled','false');
		oBackout.setAttribute('class','btn btn-light');
		oBackout.setAttribute('aria-disabled','false');

	}

	//下棋：对弈
	oPlayChess.onclick = function (){
		    absoluteChess = 2;
			wqBoard.onclick = function (e){
			var oEvent = e||event; 
			oEvent.preventDefault();
		    var x = oEvent.offsetX-Math.floor( CELL / 3 );
	    	var y = oEvent.offsetY-Math.floor( CELL / 3 );
		    var i = Math.floor( x / CELL );
    		var j = Math.floor( y / CELL );
			onOneStep( context , CELL , RADIUS , x , y , chess );
			if(absoluteChess != 2){
					chess = absoluteChess;
				}else{
						
					chess = !chessStatusData[i][j];
				}
		}
		this.setAttribute('class','btn btn-secondary disable') ;
		this.setAttribute('aria-disabled','true');
		oTakePiece.setAttribute('class','btn btn-light') ;
		oTakePiece.setAttribute('aria-disabled','false');
		oBlackChess.setAttribute('class','btn btn-dark');
		oBlackChess.setAttribute('aria-disabled','false');
		oWhiteChess.setAttribute('class','btn btn-dark');
		oWhiteChess.setAttribute('aria-disabled','false');

	}
	//下棋：黑棋
	oBlackChess.onclick = function (){
		chess = 1;
		absoluteChess = 1;
		wqBoard.onclick = function (e){
			var oEvent = e||event; 
			oEvent.preventDefault();
		    var x = oEvent.offsetX-Math.floor( CELL / 3 );
	    	var y = oEvent.offsetY-Math.floor( CELL / 3 );
		    var i = Math.floor( x / CELL );
    		var j = Math.floor( y / CELL );
			onOneStep( context , CELL , RADIUS , x , y , chess );
			if(absoluteChess != 2){
					chess = absoluteChess;
				}else{
						
					chess = !chessStatusData[i][j];
				}
		}
		this.setAttribute('class','btn btn-secondary disable') ;
		this.setAttribute('aria-disabled','true');
		oTakePiece.setAttribute('class','btn btn-light') ;
		oTakePiece.setAttribute('aria-disabled','false');
		oPlayChess.setAttribute('class','btn btn-dark');
		oPlayChess.setAttribute('aria-disabled','false');
		oWhiteChess.setAttribute('class','btn btn-dark');
		oWhiteChess.setAttribute('aria-disabled','false');

	}
	//下棋：白棋
	oWhiteChess.onclick = function (){
		chess = 0;
		absoluteChess = 0;
		wqBoard.onclick = function (e){
			var oEvent = e||event; 
			oEvent.preventDefault();
		    var x = oEvent.offsetX-Math.floor( CELL / 3 );
	    	var y = oEvent.offsetY-Math.floor( CELL / 3 );
		    var i = Math.floor( x / CELL );
    		var j = Math.floor( y / CELL );
			onOneStep( context , CELL , RADIUS , x , y , chess );
			if(absoluteChess != 2){
					chess = absoluteChess;
				}else{
						
					chess = !chessStatusData[i][j];
				}
		}
		this.setAttribute('class','btn btn-secondary disable') ;
		this.setAttribute('aria-disabled','true');
		oTakePiece.setAttribute('class','btn btn-light') ;
		oTakePiece.setAttribute('aria-disabled','false');
		oPlayChess.setAttribute('class','btn btn-dark');
		oPlayChess.setAttribute('aria-disabled','false');
		oBlackChess.setAttribute('class','btn btn-dark');
		oBlackChess.setAttribute('aria-disabled','false');

	}
	//撤销棋子
	oBackout.onclick = function (){
		topAlert('请点击要撤销的棋子','info');
		wqBoard.onclick = function (e){
			var oEvent = e||event;
			oEvent.preventDefault();
	    	var x = oEvent.offsetX-Math.floor( CELL / 3 );
    		var y = oEvent.offsetY-Math.floor( CELL / 3 );
		    var i = Math.floor( x / CELL );
    		var j = Math.floor( y / CELL );
    		
    		if(chessStatusData[i][j] != 2 ){
    			clearChess( context , i , j , CELL , RADIUS );
    			chessStatusData[i][j] = 2;
    			for(var n = 0, len = curData.length; n < len ; n++){
    				if(curData[n].x == i && curData[n].y == j){
    					curData.splice(n, 1);
    					break;
    				}
    			}
    		}
    		
		}
		this.setAttribute('class','btn btn-secondary disable') ;
		this.setAttribute('aria-disabled','true');
		oPlayChess.setAttribute('class','btn btn-dark');
		oPlayChess.setAttribute('aria-disabled','false');
		oBlackChess.setAttribute('class','btn btn-dark');
		oBlackChess.setAttribute('aria-disabled','false');
		oWhiteChess.setAttribute('class','btn btn-dark');
		oWhiteChess.setAttribute('aria-disabled','false');
		oTakePiece.setAttribute('class','btn btn-light');
		oTakePiece.setAttribute('aria-disabled','false');

	} 

	//恢复
	// oForward.onclick = function (){
	// 	if( backoutData != '' )
	// 	{
	// 		backoutData.reverse();
	// 		for(var i = 0 , len = backoutData.length ; i < len ; i++ ){
	// 			if( chessStatusData[backoutData[i].x][backoutData[i].y] == 2){
	// 				oneStep( context , CELL , RADIUS , backoutData[i].x ,  backoutData[i].y , backoutData[i].status);
	// 				chess = !chess;
	// 			}
	// 		}
	// 		backoutData = [];
	// 		oForward.setAttribute('class','btn btn-secondary disable') ;
	// 		oForward.setAttribute('aria-disabled','true');
	// 	}

	// }
}
//绘制棋盘的方法
function drawChessBoard( context , COL , CELL ){
	var lineLength = (COL+1) * CELL;
	for( var i = 0 ; i < COL ; i++ )
	{
		//横线
		context.beginPath();
		context.moveTo( CELL , CELL * ( i + 1 ) );
		context.lineTo( lineLength - CELL , CELL * ( i + 1 ) );
		// context.closePath();
		// context.stroke();
		
		//竖线
		// context.beginPath();
		context.moveTo( CELL * ( i + 1 ) , CELL );
		context.lineTo( CELL * ( i + 1 ) , lineLength - CELL );
		context.closePath();
		context.stroke();
		

		//绘制数字和英文坐标
		context.font="0.7rem Georgia";
		context.strokeText( i + 1 , 0 , CELL * ( i + 1 ) );
		context.strokeText( letterArr [i] , CELL * ( i + 1 ) , Math.floor( 2 * CELL / 3 ) );
	}
	//绘制棋盘星位和天元
	for( var i = 3 ; i <= 15 ; i = i+6 )
	{
		for( var j = 3 ; j <= 15 ; j = j+6 )
		{
			drawStar( context , CELL , i , j );
		}
	}

}

//星位小黑点的绘制方法
function drawStar (context , CELL , x , y){
	context.beginPath();
	context.arc(CELL * ( x + 1 ) , CELL * ( y + 1 ) , 3 , 0 , 2 * Math.PI);
	context.closePath();

	context.fillStyle = "#000000";
	context.fill();
}

//下一步棋
function onOneStep( context , CELL , RADIUS , x , y , chess ){
    var i = Math.floor( x / CELL );
    var j = Math.floor( y / CELL );
    if(chessStatusData[i][j] == 2){
   		 oneStep( context , CELL , RADIUS , i , j , chess );
    }
} 

//画一步棋
function oneStep (context , CELL , RADIUS , i , j , chess){
	context.beginPath();
	context.arc(CELL * ( i + 1 ) , CELL * ( j + 1 ) , RADIUS , 0 , 2 * Math.PI);
	context.closePath();

	var gradient = context.createRadialGradient(CELL * ( i + 1 )+2 , CELL * ( j + 1 )-2 , RADIUS , CELL * ( i + 1 )+2 , CELL * ( j + 1 )-2 , 0 );
	var status = 1;
	if(chess){
    	gradient.addColorStop(0,'#0A0A0A');
    	gradient.addColorStop(1,'#636767');
    	status = 1;
	}else{
		gradient.addColorStop(0,'#D1D1D1');
    	gradient.addColorStop(1,'#F9F9F9');
    	status = 0;
	}
	context.fillStyle = gradient;
	context.fill(); 
	chessStatusData[i][j] = status;
	var data = {'x':i,'y':j,'status':status};
	curData.push(data);
}

//重画清掉棋子时消失的线
function reDrawline( context , x , y , CELL , RADIUS ){
	context.strokeStyle = "#333333";
	var x1 = CELL * ( x + 1 );
	var y1 = CELL * ( y + 1 );
	if( x == 0 ){
		x2 = CELL * ( x + 1 ) - 1;
		x3 = CELL * ( x + 1 ) + RADIUS + 1;
	}else if( x == 18 ){
		x2 = CELL * ( x + 1 ) - RADIUS - 1;
		x3 = CELL * ( x + 1 ) + 1;
	}else{
		x2 = CELL * ( x + 1 ) - RADIUS - 1;
		x3 = CELL * ( x + 1 ) + RADIUS + 1;
	}
	context.beginPath();
	context.moveTo( x2 , y1  );
	context.lineTo( x3 , y1  );
	context.closePath();
	context.stroke();
	
	if( y == 0 ){
		y2 =  CELL * ( y + 1 ) - 1;
		y3 = CELL * ( x + 1 ) + RADIUS + 1;
	}else if( y == 18 ){
		y2 = CELL * ( y + 1 ) - RADIUS - 1;
		y3 = CELL * ( y + 1 ) + 1;
	}else{
		y2 = CELL * ( y + 1 ) - RADIUS - 1;
		y3 = CELL * ( y + 1 ) + RADIUS + 1;
	}
	context.beginPath();
	context.moveTo( x1  , y2 );
	context.lineTo( x1  , y3 );
	context.closePath();
	context.stroke();
	//是否画星位
	var ifDrawStar = false;
	for( var i = 3 ; i <= 15 ; i = i+6 )
	{
		if( x == i ){
			for( var j = 3 ; j <= 15 ; j = j+6 )
			{
				if( y == j ){
					ifDrawStar = true;
					break;
				}
			}
		}

	}
	if(ifDrawStar){
		drawStar( context , CELL , x , y );
	}
	//是否画数字或字母
	if( x == 0 )
	{
		context.strokeText( y + 1 , 0 , CELL * ( y + 1 ) );
		context.strokeText( letterArr [y] , CELL * ( y + 1 ) , Math.floor( 2 * CELL / 3 ) );
	}
	if( y == 0 )
	{
		context.strokeText( x + 1 , 0 , CELL * ( x + 1 ) );
		context.strokeText( letterArr [x] , CELL * ( x + 1 ) , Math.floor( 2 * CELL / 3 ) );
	}
}
//清掉某个位置的棋子
function clearChess(context ,  x , y , CELL , RADIUS ){
		//清掉棋子
		context.clearRect( x * CELL + CELL - RADIUS - 1, y * CELL + CELL - RADIUS - 1, 2 * RADIUS + 2, 2 * RADIUS + 2 );
		//画回清掉的线
		reDrawline( context , x , y , CELL , RADIUS );
}
//棋盘初始化方法 
function wqBoardInit( canvas , COL , CELL , RADIUS , CANVAS_WIDTH ){
	//获取canvas内容
	var context = canvas.getContext('2d');
	context.clearRect(0 , 0 , CANVAS_WIDTH , CANVAS_WIDTH);
	context.strokeStyle = "#000000";
	context.lineWidth = 1;
	context.lineCap = 'round';
	// var backgroundImg = document.getElementById('backgroundImg');
	// context.drawImage(backgroundImg,0,0,CANVAS_WIDTH,CANVAS_WIDTH);
	drawChessBoard( context , COL , CELL);
}