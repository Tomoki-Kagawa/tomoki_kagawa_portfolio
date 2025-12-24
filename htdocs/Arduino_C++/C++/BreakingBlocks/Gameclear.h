#ifndef GAMECLEAR_H_
#define GAMECLEAR_H_
#include <QtGui>
#include <QtWidgets> 
#include <QDialog>
#include <QPainter>
#include <QFont>

class Gameclear : public QDialog
{
	Q_OBJECT
public:
	explicit Gameclear(QWidget* parent = 0);
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
