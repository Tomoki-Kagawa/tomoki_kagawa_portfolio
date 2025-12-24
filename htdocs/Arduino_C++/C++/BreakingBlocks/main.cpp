#include <QApplication>
#include <QtGui>
#include <QWidget>
#include "BreakingBlock.h"
 
int main( int argc, char **argv )
{
	QApplication app(argc, argv);
	BreakingBlock* breakingblock = new BreakingBlock;
	breakingblock->show();
	return app.exec();
}
