#define USE_ARDUINO_INTERRUPTS true   
#include <LiquidCrystal.h>

// PINの設定
int pulsePIN = 3;     // パルスセンサー出力をアナログピンの0番指定
//int pulsePIN = 2;   // 予備パルスセンサ
int beep = 8;         // スピーカー用のデジタルピン8番
int LED = LED_BUILTIN;// Lチカ用のデジタルピンを13番指定

//LCD設定  
int rs = 4;
int en = 6;
int d4 = 10; 
int d5 = 11;
int d6 = 12; 
int d7 = 9;
LiquidCrystal lcd(rs, en, d4, d5, d6, d7);

//変数の設定
int pulse;            // analogReadで読み取るpulse値の格納変数
int threshold = 550;  // ピークを検出する閾値
bool beeped = false;  // ビープを鳴らしたかどうか
int bpm = 0;
int sound =1000;//330//70;// スピーカー

float nowTime = 0;
float lastTime = 0; 
float interval=0;

float filtervalue=0;
float lastfiltervalue=0;
float alpha=0.2;

void setup() {
  pinMode(LED,OUTPUT); // 13番ピンを出力指定
  Serial.begin(9600);  // シリアルポートを9600bpsで指定
  lcd.begin(16, 2);
}

void loop() {
  nowTime = millis();
  pulse = analogRead(pulsePIN);
  
  //パルス値をフィルタ
  filtervalue=alpha*pulse+(1-alpha)*lastfiltervalue;
  Serial.println((int)filtervalue);
  //Serial.println();

  // ピーク検出
  if ((int)filtervalue > threshold &&  lastfiltervalue>filtervalue  && beeped==false) {
    tone(beep, sound, 50); // 
    beeped = true;  // 1回鳴らしたフラグを立てる
    
    // BPM計算
    if (lastTime > 0) {
      float interval = nowTime - lastTime; // ms
      bpm = 60000 / interval; // 1分あたりの心拍数
    }
    lastTime = nowTime;
     digitalWrite(LED,HIGH);
  }

  // 閾値を下回ったら次のピークに備える
  if ((int)filtervalue < threshold) {
    beeped = false;
    digitalWrite(LED,LOW);
  }

  //lcd初期化 
  lcd.clear();

  //LCD表示
  lcd.print("BPM:");
  lcd.print(bpm);

  //Modeせってい
  if(bpm>75){
    lcd.setCursor(0,2);
    lcd.print("Mode:HIGH");
  }
  else{
    lcd.setCursor(0,2);
    lcd.print("Mode:LOW");
  }

  lastfiltervalue=filtervalue;

  delay(10);

}
