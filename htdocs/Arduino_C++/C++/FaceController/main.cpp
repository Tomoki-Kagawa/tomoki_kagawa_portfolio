#include <yarp/os/all.h>
#include <opencv2/opencv.hpp>
using namespace yarp::os;
using namespace cv;
using namespace std;

int main() {
    Network yarp;
    
    if (!yarp.checkNetwork()) {
        	cerr<<"YARPが繋がっていません"<<endl;
        	return 1;
    }
    
    BufferedPort<Bottle>face_sender;
    face_sender.open("/FaceController/face_sender");
    string input="None";
    // カメラ
    VideoCapture cap(0);

    // Cascade ファイルの読み込み
    CascadeClassifier cascade;
    cascade.load("/usr/share/opencv4/haarcascades/haarcascade_frontalface_default.xml"); 

    Mat frame, gray,frame_flip;
    while(true) {
    	Bottle& f_value=face_sender.prepare();
        
        cap >> frame;
        if(frame.empty()) break;

        cvtColor(frame, gray, COLOR_BGR2GRAY);
        vector<Rect>face;
        cascade.detectMultiScale(gray, face, 1.1, 3, 0, Size(150, 150));
       	
       	//反転
        flip(frame,frame_flip, 1);
        //画面の中心とゲームのコントロールができる範囲の境界線を表示
        line(frame_flip, Point(120, 0), Point(120, 480), Scalar(0,0,255), 3, 4);
        line(frame_flip, Point(320, 0), Point(320, 480), Scalar(0,255,0), 3, 4);
        line(frame_flip, Point(520, 0), Point(520, 480), Scalar(0,0,255), 3, 4);
        //画面の上に文字を表示
        putText(frame_flip, "Left", Point(70,50), FONT_HERSHEY_SIMPLEX, 1.2, Scalar(255,0,0), 4, 4);
        putText(frame_flip, "Center", Point(260,50), FONT_HERSHEY_SIMPLEX, 1.2, Scalar(255,0,0), 4, 4);
        putText(frame_flip, "Right", Point(470,50), FONT_HERSHEY_SIMPLEX, 1.2, Scalar(255,0,0), 4, 4);
        
        // 画像の幅
        int width = frame.cols; 
        
        if (!face.empty()) {
            // 左右反転
            Rect face_flip;
            face_flip.x=width-(face[0].x+face[0].width);
            face_flip.y=face[0].y;
            face_flip.width=face[0].width;
            face_flip.height=face[0].height;
            
            //顔を四角く囲う
            int x=face[0].x+face[0].width/2;
            if(x<120){
                rectangle(frame_flip, face_flip, Scalar(255, 0, 255), 5);
            }
            else if(520<x){
                rectangle(frame_flip, face_flip, Scalar(255, 0, 255), 5);
            }
            else{
                rectangle(frame_flip, face_flip, Scalar(0, 255, 255), 5);
            }
            
            //値の送信
            f_value.clear();
            f_value.addInt32(x);
            face_sender.write();
            cout << x <<endl;
        }
        else{
            input="None";
            cout << "None"<< endl;
        }
        //画面表示
        imshow("FaceController", frame_flip);
        //キーボードのqを押したら終了
        if(waitKey(30) == 'q') break;
    }
    face_sender.close();
    return 0;
}
