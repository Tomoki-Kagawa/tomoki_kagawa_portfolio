#ifndef GAMEOVER_H_
#define GAMEOVER_H_
#include <QtGui>
#include <QtWidgets> 
#include <QDialog>
#include <QPainter>
#include <QFont>

class Gameover : public QDialog
{
	Q_OBJECT
public:
	explicit Gameover(QWidget* parent = 0);
protected:
	void paintEvent(QPaintEvent *event) override;
signals:
	void retryRequest();
public slots:
	void retryButtonClick();
private:
	QPushButton *retryButton; 
	QTimer *timer;
	QGridLayout* gridLayout;
	QWidget* graphicsView;    
};

#endif
