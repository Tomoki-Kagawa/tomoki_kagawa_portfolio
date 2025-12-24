#include <QApplication>
#include <QtGui>
#include <QWidget>
#include "SerialCommunication.h"
 
int main( int argc, char **argv )
{
  QApplication app(argc,argv);
  SerialCommunication* serialcommunication=new SerialCommunication;
  serialcommunication->show();
  return app.exec();
}
