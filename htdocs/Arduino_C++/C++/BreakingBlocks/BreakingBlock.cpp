#include <iostream>
#include <fcntl.h>
#include <termios.h>
#include <unistd.h>
#include <cstring>
#include <cerrno>
#include <stdlib.h>
#include <string>

#include <yarp/os/all.h>

#include <QDialog>
#include <QGridLayout>
#include <QPushButton>
#include <QWidget>
#include <QTimer>
#include <QPainter>
#include <QFont>
#include <QPaintEvent>

#include "BreakingBlock.h"
#include "Gameover.h"
#include "Gameclear.h"

using namespace yarp::os;
using namespace std;
//前処理
BreakingBlock::BreakingBlock(QWidget* parent): QDialog(parent), gameover(nullptr)
{
	Network yarp;

	if (!yarp.checkNetwork()) {
		cerr << "YARPが繋がっていません" << endl;
	}
	face_receiver.open("/BreakingBlock/face_receiver");
	pulse_receiver.open("/BreakingBlock/pulse_receiver");

	resize(410, 450);
	gridLayout = new QGridLayout(this);
	gridLayout->setObjectName(QString::fromUtf8("gridLayout"));
	startButton = new QPushButton("START");
	startButton->setFixedSize(390, 25);
	gridLayout->addWidget(startButton, 1, 0, 1, 2);
	graphicsView = new QWidget;
	gridLayout->addWidget(graphicsView, 0, 0, 1, 2);
	setLayout(gridLayout);
	connect(startButton, SIGNAL(clicked()), this, SLOT(startButtonClick()));
	timer=new QTimer(this);
	connect(timer, SIGNAL(timeout()), graphicsView, SLOT(update())); 
	timer->start(10); 
	gameover = new Gameover(this);
	gameclear = new Gameclear(this);
	connect(gameover, &Gameover::retryRequest, this, &BreakingBlock::restartGame);
	connect(gameclear, &Gameclear::retryRequest, this, &BreakingBlock::restartGame);

	for (int x_block = 0; x_block < 11; x_block++){
	    	for(int y_block = 0; y_block<10; y_block++){	
	      		block[x_block][y_block]=1;
	    	}
	}
}
//スタートボタンが押されたときの処理
void BreakingBlock::startButtonClick()
{
	if(x==0&&y==0){
		start = true;
		x_type = 1;
		y_type = 1;
	}
}
//リトライボタンが押されたとき
void BreakingBlock::restartGame(){
	x=0;
	y=0;
	clearcnt=0;
	for (int x_block = 0; x_block < 11; x_block++) {
		for (int y_block = 0; y_block < 10; y_block++) {
			block[x_block][y_block] = 1;
		}
	}
	update(); 
}
//キーボード操作をする場合
void BreakingBlock::keyPressEvent(QKeyEvent *event) {
	if (event->key() == Qt::Key_A) {
		x_board-=10;
	}
	if (event->key() == Qt::Key_D) {
		x_board+=10;
	}
	if(180<=x_board)x_board=180;
	if(-180>=x_board)x_board=-180;
	update();
}
//描画処理
void BreakingBlock::paintEvent(QPaintEvent *event)
{
	QPainter painter(this);

	QPen penblack(Qt::black);
	penblack.setWidth(10);

	QPen penwhite(Qt::white);
	penwhite.setWidth(10);

	painter.setPen(penblack);
	QRect rect(x0, y0, width, height);
	painter.drawRect(rect);
	QBrush brushwhite(Qt::white);    		 
	painter.fillRect(rect, brushwhite);  

	pulse_value = pulse_receiver.read(false);
	if (pulse_value != nullptr)mode=pulse_value->toString();
	
	//mode判定
	if(start==true){
		if(mode=="Low"){
			//左壁
		    if(x_type==1)x++;
		    //右壁
		    else if(x_type==2)x--;
		    else x=x;    	
		    //下壁
		    if(y_type==1)y++;
		    //上壁
		    else if(y_type==2)y--;
		    else y=y;
		}
		if(mode=="High"){
			//左壁
			if(x_type==1)x+=2;
			//右壁
			else if(x_type==2)x-=2;
			else x=x;
			//下壁
			if(y_type==1)y+=2;
			//上壁
			else if(y_type==2)y-=2;
			else y=y;
		}
		//右壁
		if(x>=195)x_type=2;
		//左壁
		else if(x<=-195)x_type=1;
		//下壁
		if(y<=-25){
			x_type=0;
			y_type=0;
			start=false;
			painter.end();
			gameover->show();
		}
		//上壁
		else if(y>=365)y_type=2;
	}
	//ブロック生成と当たり判定
	for (int x_block = 0; x_block < 11; x_block++){
		for(int y_block = 0; y_block<10; y_block++){
			x_block_value[x_block]=11+x_block*35;
			y_block_value[y_block]=11+y_block*20;
		    	if(block[x_block][y_block]==1){
		    	
			      	QRect blocks(x_block_value[x_block], y_block_value[y_block], 34, 19); 
					QPen penred(Qt::red);
					penwhite.setWidth(1);

			    	QBrush brush(Qt::black); 
			    	painter.fillRect(blocks, brush); 

			    	if(x_block_value[x_block]<=200-x&&200-x<=x_block_value[x_block]+34&&y_block_value[y_block]+16<=375-y&&375-y<=y_block_value[y_block]+19){
			    		block[x_block][y_block]=0;
			    		y_type=2;
			    	}	    		
				if(x_block_value[x_block]<=200-x&&200-x<=x_block_value[x_block]+34&&y_block_value[y_block]<=375-y&&375-y<=y_block_value[y_block]+3){
			    		block[x_block][y_block]=0;
			    		y_type=1;
			    	}	    	
				if(x_block_value[x_block]<=200-x&&200-x<=x_block_value[x_block]+3&&y_block_value[y_block]<=375-y&&375-y<=y_block_value[y_block]+19){
			    		block[x_block][y_block]=0;
			    		x_type=1;
			    	}	
				if(x_block_value[x_block]+31<=200-x&&200-x<=x_block_value[x_block]+34&&y_block_value[y_block]<=375-y&&375-y<=y_block_value[y_block]+19){
					block[x_block][y_block]=0;
					x_type=2;
			    	}	
			    	if(block[x_block][y_block]==0){
		        		clearcnt++;
		    		}	
		    	}
		}
	}
	//クリア時ウインドウ表示
	if(clearcnt==110)gameclear->show();

	//FaceController情報を変換
	face_value = face_receiver.read(false);
	if (face_value != nullptr) {
		int x_position=face_value->get(0).asInt32()-320;
		x_board=-x_position;
	}
	//ボードの制限
	if(180<=x_board)x_board=180;
	if(-180>=x_board)x_board=-180;
	penblack.setWidth(5);
	painter.setPen(penblack);
	painter.drawPoint(200-x,375-y);
	//ボードの反射
	if(-20+-x_board<=x&&x<=-20+-x_board+40&&0>=y&&y>=-5)y_type=1;

	//ターミナル表示
	cout << "mode:" << mode << " board:" << x_board << endl;
	QRect board(180+x_board, 380, 40, 5); 
	painter.drawRect(board);  
	QBrush brush(Qt::black); 
	painter.fillRect(board, brush);
}
