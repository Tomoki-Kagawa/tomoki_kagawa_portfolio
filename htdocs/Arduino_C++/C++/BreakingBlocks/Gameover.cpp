#include <QtGui>
#include <QtWidgets> 
#include <QWidget>
#include <QGridLayout>
#include <QPainter>
#include <QFont>
#include "Gameover.h"
#include "BreakingBlock.h"
//前準備
Gameover::Gameover(QWidget* parent) : QDialog(parent)
{
	resize(400, 120);
	gridLayout = new QGridLayout(this); 
	graphicsView = new QWidget(this);
	timer=new QTimer(this);
	retryButton = new QPushButton("RETRY");
	retryButton->setFixedSize(390, 25);
	gridLayout->addWidget(retryButton, 1, 0, 1, 2);
	gridLayout->addWidget(graphicsView, 0, 0, 1, 2);
	setLayout(gridLayout);
	connect(retryButton,SIGNAL(clicked()), this, SLOT(retryButtonClick()));
	timer->start(10); 
}
//リトライボタンを押したとき
void Gameover::retryButtonClick()
{
	emit retryRequest(); 
	this->close();
}
//描画処理
void Gameover::paintEvent(QPaintEvent *event){
    QPainter painter(this);
    painter.setPen(Qt::black);
    painter.setFont(QFont("Arial", 55));
    painter.drawText(5, 70, "Game Over!");
}
