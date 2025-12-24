package com.example.shockmaze;

import android.content.Context;
import android.content.Intent;
import android.graphics.Canvas;
import android.graphics.Color;
import android.graphics.Paint;
import android.hardware.Sensor;
import android.hardware.SensorEvent;
import android.hardware.SensorEventListener;
import android.os.Bundle;
import android.util.Log;
import android.view.SurfaceHolder;
import android.view.View;
import androidx.appcompat.app.AppCompatActivity;
import android.content.pm.ActivityInfo;
import android.hardware.SensorManager;
import android.view.SurfaceView;
import java.util.ArrayList;
import java.util.Random;

public class playActivity extends AppCompatActivity implements SensorEventListener, SurfaceHolder.Callback{

    SensorManager mSensorManager;
    Sensor mAccSensor;
    SurfaceHolder holder;
    public float startX=500;
    public float startY=500;
    public float sensorX=0;
    public float sensorY=0;
    public int SurfaceWidth = 1050;
    public int SurfaceHeight = 1050;
    public float currentBallX=500;
    public float currentBallY=500;
    private float radius = 50;
    private final float MOVEMENT_FACTOR = 0.8f; // ボールの移動速度
    private int level=0;
    private int result=0;
    ArrayList<Edge> edge =new ArrayList<>();
    public float lastX=0;
    public float lastY=0;
    Random rndLen = new Random();
    Random rndDir = new Random();
    public float randomLength = 0;
    public int randomDirection=0;
    public int lastRandomDirection=0;
    public float thickness =0;
    public int count =0;
    public int safety =0;
    public float LengthX=0;
    public float LengthY=0;
    public boolean random=false;
    public boolean overlap=false;
    public boolean onRoad=false;
    public float bound =0;
    public float top=500;
    public float left=500;
    public float bottom=500;
    public float right=500;
    public int last=0;
    public boolean generate=false;
    public int generateCount=0;
    private int retry=0;

    //道の端
    class Edge {
        float left,top,right,bottom;
        Edge(float left, float top,float right,float bottom) {
            this.left = left;
            this.top = top;
            this.right=right;
            this.bottom=bottom;
        }
    }

    //表示された時
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        //画面の固定
        setRequestedOrientation(ActivityInfo.SCREEN_ORIENTATION_PORTRAIT);
        //レイアウト指定
        setContentView(R.layout.activity_play);

        mSensorManager = (SensorManager) getSystemService(Context.SENSOR_SERVICE);
        if (mSensorManager != null) {
            mAccSensor = mSensorManager.getDefaultSensor(Sensor.TYPE_ACCELEROMETER);
        }
        //情報の受け取り
        Intent intent = getIntent();
        level = intent.getIntExtra("level", 0);

        //サーフェスビューの追加
        SurfaceView surfaceView =  (SurfaceView) findViewById(R.id.gameView);
        holder = surfaceView.getHolder();
        holder.addCallback(this);
    }

    //ボタンをクリックされた時
    public void onClick(View v) {
        int viewId = v.getId();
        if (viewId == R.id.gameStart) {
            mSensorManager.registerListener(this, mAccSensor, SensorManager.SENSOR_DELAY_GAME);
        }
    }

    //加速度センサの更新
    @Override
    public void onSensorChanged(SensorEvent event) {
        if (event.sensor.getType() == Sensor.TYPE_ACCELEROMETER) {
            //Log表示
            Log.d("MainActivity",
                    "x=" + String.valueOf(event.values[0]) +
                            "y=" + String.valueOf(event.values[1]));
            //センサの値を変数に代入
            sensorX = -event.values[0];
            sensorY = event.values[1];

            // ボールの位置を更新
            currentBallX += sensorX * MOVEMENT_FACTOR;
            currentBallY += sensorY * MOVEMENT_FACTOR;

            // 画面の端でボールを停止させる（制限）
            if (currentBallX < radius) currentBallX = radius;
            if (currentBallX > SurfaceWidth - radius) currentBallX = SurfaceWidth - radius;
            if (currentBallY < radius) currentBallY = radius;
            if (currentBallY > SurfaceHeight - radius) currentBallY = SurfaceHeight - radius;

            onRoad=false;
            //道が重なっていないか
            for (Edge way : edge) {
                if(way.left+radius<=currentBallX&&currentBallX<=way.right-radius&&way.top+radius<=currentBallY&&currentBallY<=way.bottom-radius){
                    onRoad=true;
                }
            }
            //壁に触れたら
            if(!onRoad)
            {
                result=0;
                Intent intent = new Intent(playActivity.this, endActivity.class);
                intent.putExtra("result", result);
                startActivityForResult(intent, result);
                if (mSensorManager != null) {
                    mSensorManager.unregisterListener(this);
                }
                finish();
            }
            //ゴール地点
            if(lastX-2<=currentBallX&&currentBallX <= lastX+2 &&lastY-2<=currentBallY&&currentBallY <= lastY+2) {
                result=1;
                Intent intent = new Intent(playActivity.this, endActivity.class);
                intent.putExtra("result", result);
                startActivityForResult(intent, result);
                if (mSensorManager != null) {
                    mSensorManager.unregisterListener(this);
                }
                finish();
            }
            // Surfaceが準備できていれば描画
            if (holder.getSurface().isValid()) {
                drawCanvas();
            }
        }
    }

    //センサの精度が変わった時
    @Override
    public void onAccuracyChanged(Sensor sensor, int accuracy) {}

    //ステージ生成
    @Override
    public void surfaceCreated(SurfaceHolder holder) {
        //簡単
        if(level==1) {
            radius = 20;
            thickness =50;
            bound=(SurfaceHeight-thickness)/2;
            last=9;
        }
        //普通
        else if(level==2) {
            radius = 20;
            thickness = 40;
            bound=(SurfaceHeight-thickness)/2;
            last=11;
        }
        //難しい
        else if(level==3) {
            radius = 20;
            thickness=25;
            bound=(SurfaceHeight-thickness)/2;
            last=13;
        }
        generateCount=0;
        //ステージ生成成功時
        while (!generate) {
            //初期化
            edge.clear();
            startX = startY = LengthX = LengthY = 500;
            currentBallX = currentBallY = 500;
            left = right = top = bottom = 500;
            generateCount++;
            lastRandomDirection = rndDir.nextInt(4);
            safety = 0;
            count = 0;
            //30回生成失敗時
            if (generateCount >= 30) {
                retry = 1;
                if (mSensorManager != null) {
                    mSensorManager.unregisterListener(this);
                }
                generate = true;
                Intent intent = new Intent(playActivity.this, levelActivity.class);
                intent.putExtra("retry", retry);
                startActivityForResult(intent, retry);
                finish();
            }
            //道生成ループ
            while (count < last && safety < 500) {
                safety++;
                //乱数生成
                //長さ
                randomLength = thickness + rndLen.nextFloat() * (bound / 2 + thickness);
                //方向
                randomDirection = rndDir.nextInt(4);
                //右
                if (randomDirection == 0 && (lastRandomDirection == 1 || lastRandomDirection == 3)) {
                    LengthX = startX + randomLength;
                    LengthY = startY;

                    left = startX - thickness;
                    top = startY - thickness;
                    right = LengthX + thickness;
                    bottom = LengthY + thickness;

                    random = true;
                }
                //下
                else if (randomDirection == 1 && (lastRandomDirection == 0 || lastRandomDirection == 2)) {
                    LengthX = startX;
                    LengthY = startY + randomLength;

                    left = startX - thickness;
                    top = startY - thickness;
                    right = LengthX + thickness;
                    bottom = LengthY + thickness;

                    random = true;
                }
                //左
                else if (randomDirection == 2 && (lastRandomDirection == 1 || lastRandomDirection == 3)) {
                    LengthX = startX - randomLength;
                    LengthY = startY;

                    left = LengthX - thickness;
                    top = LengthY - thickness;
                    right = startX + thickness;
                    bottom = startY + thickness;

                    random = true;
                }
                //上
                else if (randomDirection == 3 && (lastRandomDirection == 0 || lastRandomDirection == 2)) {
                    LengthX = startX;
                    LengthY = startY - randomLength;

                    left = LengthX - thickness;
                    top = LengthY - thickness;
                    right = startX + thickness;
                    bottom = startY + thickness;

                    random = true;
                }
                overlap = false;

                //道重ね検知
                float margin = thickness / 2;
                for (int i = 0; i < edge.size(); i++) {
                    if (i == edge.size() - 1) continue;
                    Edge way = edge.get(i);
                    if (!(right < way.left - margin || left > way.right + margin || bottom < way.top - margin || top > way.bottom + margin)) {
                        overlap = true;
                        break;
                    }
                }

                //ステージ生成できる場合
                if (0 <= left && left <= SurfaceWidth
                        && 0 <= right && right <= SurfaceWidth
                        && 0 <= top && top <= SurfaceHeight
                        && 0 <= bottom && bottom <= SurfaceHeight
                        && random && !overlap) {

                    //生成位置の保存
                    edge.add(new Edge(left, top, right, bottom));
                    startX = LengthX;
                    startY = LengthY;
                    lastRandomDirection = randomDirection;

                    count++;
                    random = false;
                    overlap = false;
                    //ゴール地点の位置
                    if (count == last) {
                        if (lastRandomDirection == 0) {
                            lastX = LengthX - thickness / 2;
                            lastY = LengthY;
                        } else if (lastRandomDirection == 1) {
                            lastX = LengthX;
                            lastY = LengthY - thickness / 2;
                        } else if (lastRandomDirection == 2) {
                            lastX = LengthX + thickness / 2;
                            lastY = LengthY;
                        } else if (lastRandomDirection == 3) {
                            lastX = LengthX;
                            lastY = LengthY + thickness / 2;
                        }
                        //描画
                        drawCanvas();
                        generate = true;
                    }
                }
            }
        }
    }

    //描画が変更される時
    @Override
    public void surfaceChanged(SurfaceHolder holder, int format, int width, int height) {}

    //描画が破棄される時
    @Override
    public void surfaceDestroyed(SurfaceHolder holder) {
        if(mSensorManager != null) {
            mSensorManager.unregisterListener(this);
        }
    }

    //描画
    protected void drawCanvas() {
        Canvas canvas =holder.lockCanvas();
        if (canvas != null) {

            canvas.drawColor(Color.GRAY);
            Paint paintBlack=new Paint();
            paintBlack.setColor(Color.BLACK);
            paintBlack.setStyle(Paint.Style.FILL);
            Paint paintYellow = new Paint();
            paintYellow.setColor(Color.YELLOW);
            paintYellow.setStyle(Paint.Style.FILL);
            Paint paintRed = new Paint();
            paintRed.setColor(Color.RED);
            paintRed.setStrokeWidth(30);

            //道
            for (Edge way : edge) {
                canvas.drawRoundRect(way.left,way.top,way.right,way.bottom, radius ,radius ,paintYellow);
            }
            canvas.drawCircle(lastX, lastY, radius, paintRed);
            canvas.drawCircle(currentBallX, currentBallY, radius, paintBlack);

            holder.unlockCanvasAndPost(canvas);
        }
    }
}


