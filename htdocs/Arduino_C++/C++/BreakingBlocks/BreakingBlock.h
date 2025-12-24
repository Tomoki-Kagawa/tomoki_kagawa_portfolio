#ifndef MAINDIALOG_H
#define MAINDIALOG_H
#include <iostream>
#include <fcntl.h>
#include <termios.h>
#include <unistd.h>
#include <cstring>
#include <cerrno>
#include <stdlib.h>
#include <string>

#include <yarp/os/all.h>

#include "BreakingBlock.h"
#include "Gameover.h"
#include "Gameclear.h"

#include <QDialog>
#include <QGridLayout>
#include <QPushButton>
#include <QWidget>
#include <QTimer>
#include <QPainter>
#include <QFont>
#include <QPaintEvent>
#include "Gameover.h"
#include "Gameclear.h"
using namespace yarp::os;
using namespace std;

class BreakingBlock : public QDialog
{
    Q_OBJECT
public:
	BreakingBlock(QWidget* parent = 0);
	int x0 = 5;
	int y0 = 5;
	int width = 395;
	int height = 395;
	int m_on =0;
	int x=0;
	int y=0;
	int x_block = 0;
	int x_blocs = 0;
	int x_type=0;
	int y_type=0;
	bool start=false;
	int x_board=0;
	int block[11][10];
	int x_block_value[11];
	int y_block_value[10];
	int clearcnt=0;
	void MSleep(int msec);
	QVector<int> vector;
	BufferedPort<Bottle> face_receiver;
	BufferedPort<Bottle> pulse_receiver;
	string mode="Low";
	string position="None";
 
protected:
	void paintEvent(QPaintEvent *event) override;
	void keyPressEvent(QKeyEvent *event) ;
	void levelChange(string mode);
 
public slots:
	void startButtonClick();
	void restartGame(); 
private:
	QPushButton *startButton; 
	QGridLayout *gridLayout;
	QWidget *graphicsView;
	QTimer *timer;
	QDialog *dialog; 
	Gameover *gameover;
	Gameclear *gameclear; 
	Bottle* face_value;
	Bottle* pulse_value;
};
 
#endif 

