#ifndef MAINDIALOG_H
#define MAINDIALOG_H
#include <iostream>
#include <QApplication>
#include <QtGui>
#include <QWidget>
#include <QDialog> 
#include <QGridLayout>
#include <QGraphicsView>
#include <QLabel>
#include <QElapsedTimer>

#include <QGraphicsScene>
#include <QtCore/QEventLoop>
#include <QPixmap>
#include <fcntl.h>   
#include <unistd.h>  
#include <termios.h> 
#include <errno.h>   
#include <cstring>   
#include <iostream>
#include <yarp/os/all.h>
using namespace yarp::os;
using namespace std;

class SerialCommunication : public QDialog
{
	Q_OBJECT
public:
	SerialCommunication(QWidget* parent = 0);
	int m_on =0;
	int x =0;
	int b_on =0;
	int y = 0;  
	int x_reset=0;
	int last_x=0;
	int last_y=0;
	int threshold=700;
	bool pulse_flg=false;
	int time=0;
	int interval=0; 
	int nowTime=0; 
	int lastTime=0; 
	int bpm=0; 
	int average[5];
	int bpm_average=0;
	int average_count=0;
	int bpm_value=0;
	string mode="Low";
	void MSleep( int msec );
	QVector<int> vector;
	float filtervalue=0;
	float alpha=0.2;
	float lastfiltervalue=0;
	int last_y_val=0;
	int serial_port;  
	int buf[256];       
	int y_val;  
protected:
	void paintEvent(QPaintEvent *event) override;
public slots:
	void drawPointOnCanvas(int last_x ,int last_y , int x, int y, int x_reset);
	void readSerial();
private:    
	BufferedPort<Bottle> pulse_sender; 
	QString buffer;
	QString m_serialBuffer;
	QGridLayout *gridLayout;
	QWidget *graphicsView;
	QTimer *timer;
	QPixmap canvas;
};

#endif

