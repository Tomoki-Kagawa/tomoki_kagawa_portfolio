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

#include "SerialCommunication.h"

using namespace yarp::os;
using namespace std;
//前処理
SerialCommunication::SerialCommunication(QWidget* parent): QDialog(parent) {
    Network yarp;
    
    if (!yarp.checkNetwork()) {
        	cerr<<"YARPが繋がっていません"<<endl;        	
    }
    pulse_sender.open("/SerialCommunication/pulse_sender");

	resize(410, 410);
	canvas = QPixmap(400, 400);
	canvas.fill(Qt::white);

	gridLayout = new QGridLayout(this);
	graphicsView = new QWidget;
	gridLayout->addWidget(graphicsView, 0, 0, 1, 2);
	graphicsView->resize(500, 500);
	setLayout(gridLayout);

	timer = new QTimer(this);
	connect(timer, &QTimer::timeout, this, &SerialCommunication::readSerial);
	connect(timer, &QTimer::timeout, this, QOverload<>::of(&SerialCommunication::update));
	timer->start(10);

	serial_port = ::open("/dev/ttyUSB0", O_RDWR |O_NOCTTY| O_NDELAY);

	struct termios tty;
	tcgetattr(serial_port, &tty);
	cfsetospeed(&tty, B9600);
	tty.c_lflag = 0;
	tcsetattr(serial_port, TCSANOW, &tty);
}
//描いたデータを残す
void SerialCommunication::drawPointOnCanvas(int last_x ,int last_y , int x, int y, int x_reset) {
    QPainter painter(&canvas);
    QPen penblack(Qt::black);
    penblack.setWidth(5);
    painter.setPen(penblack);
    painter.drawLine(last_x,last_y,x,y);
    
    QPen penwhite(Qt::white);
    penblack.setWidth(5);
    painter.setPen(penwhite);
    painter.drawLine(x_reset,10,x_reset+1,390);
}
//シリアル通信のデータがかけたまま使わないようにする
void SerialCommunication::readSerial() {
 if (serial_port < 0) return;
    char buf[256];
    int n = ::read(serial_port, buf, sizeof(buf));
    if (n > 0) {
        m_serialBuffer.append(QByteArray(buf, n));
        while (m_serialBuffer.contains('\n')) {
            int newline_pos = m_serialBuffer.indexOf('\n');
            QString line = m_serialBuffer.left(newline_pos);
            m_serialBuffer.remove(0, newline_pos + 1);
            line = line.trimmed();
            if (!line.isEmpty()) {
                y_val = line.toInt(); 
            }
        }
    }
}
//描画
void SerialCommunication::paintEvent(QPaintEvent *event) {
  Bottle& pulse_value=pulse_sender.prepare();
  QPainter painter(this);
  QPen penblack(Qt::black);
  penblack.setWidth(10);
  painter.setPen(penblack);
  painter.drawPixmap(0, 0, canvas);

  QRect rect(5, 5, 400, 400);
  painter.drawRect(rect);

  x++;
  time++;
  y = -(y_val/3)+370;  
  x_reset=x+1;

  penblack.setWidth(5);
  painter.setPen(penblack);

  drawPointOnCanvas(last_x,last_y,x,y,x_reset);
  
  last_x=x;
  last_y=y;
  //R-R値5回分のBPMの計算  
  if(y_val<1100&&y_val>-1100){  
    //波が頂点になった時
    if (y_val > threshold && last_y_val>y_val && pulse_flg==false) {
      pulse_flg = true;              
      nowTime = time;
      
      if (lastTime > 0) {
        interval = nowTime - lastTime; 
        bpm = 6000 / interval; 
        average[average_count]=bpm;
        average_count++;
        if(average_count>=5){
          for(int i=0;i<5;i++){
            bpm_average+=average[i];
          }
          bpm_value=bpm_average/5;
          average_count=0;
        }
        bpm_average=0;
      }
      lastTime = nowTime;

    }
    last_y_val=y_val;
    
    //波が下がった時
    if (y_val < threshold) {
      pulse_flg = false;
    }
    //モードの変更
    if(bpm>75)mode="High";
    else mode="Low";
	  cout << "y_val:" << y_val << " BPM:"<< bpm <<" R-R:"<< interval << " Mode:" << mode <<endl;
  } 
  else{
  	cout << "正しい値が取れていません" << endl;
  }
  if (x >400) {
    	x = 5;
    	last_x=5;
  }
  //値の送信
  pulse_value.clear();
  pulse_value.addString(mode);
  pulse_sender.write();
}
