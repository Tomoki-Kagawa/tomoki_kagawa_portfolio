/*配列と変数*/
//演奏内容表示
let noteDisplay=$('.sound_name');
//自動演奏
let autoPlayBtn=$('.playing');
//css用クラス
let noteSelectors=['.C','.D','.E','.F','.G','.A','.B','.sharp_C','.sharp_D','.sharp_F','.sharp_G','.sharp_A'];
//表示内容
let noteElements=['C','D','E','F','G','A','B','sharp_C','sharp_D','sharp_F','sharp_G','sharp_A'];
//テキスト
let noteLabels=['ド','レ','ミ','ファ','ソ','ラ','シ','#ド','#レ','#ファ','#ソ','#ラ'];
//サウンド関数
let playNoteFns=['C_sound','D_sound','E_sound','F_sound','G_sound','A_sound','B_sound','sharp_C_sound','sharp_D_sound','sharp_F_sound','sharp_G_sound','sharp_A_sound'];
//周波数
let sound_frequency=[523.3,587.3,659.3,698.5,784.0,880.0,987.8,554.4,622.3,740.0,830.6,932.3];
//サウンドストップ関数
let stopNoteFns=['C_stop','D_stop','E_stop','F_stop','G_stop','A_stop','B_stop','sharp_C_stop','sharp_E_stop','sharp_D_stop','sharp_F_stop','sharp_G_stop','sharp_A_stop'];
//ボリューム
let volumeSlider=document.querySelector('.volume_range');
//キーボードボタン
//let keyBindings=['z','x','c','v','b','n','m','s','d','g','h','j'];
let keyBindings = {KeyZ:0,KeyX:1,KeyC:2,KeyV:3,KeyB:4,KeyN:5,KeyM:6,KeyS:7,KeyD:8,KeyG:9,KeyH:10,KeyJ:11};
//キーボタン
let keyboardInputEl=document.querySelector('.keyboard_input');

//自動演奏カウント
let isAutoPlaying=0;
//自動演奏タイマーカウント
let autoPlayIntervalId=0;
//鍵盤カウント
let activeNoteCount=0;
//鍵盤タイマーカウント
let playTimerCount=0;
//オーディオ
let audio;
//キーボード押し込みカウント
let key_count=0;
//キーボードが長押しされているか
let press_key=[];
//オーディオアクティブ確認
let active_audio=[];

/*クラスを変数に代入と長押しボタンの設定*/
for(let i=0; i<12;i++){
    noteElements[i]=$(noteSelectors[i]);
    press_key[i]=false;
}
/*鍵盤のクリック*/
for(let i=0; i<12;i++){
    noteElements[i].click(()=>{
        if(isAutoPlaying==0){
            activeNoteCount++;
            playNoteFns[i](1000);
            noteDisplay.text(noteLabels[i]);
        }
    });
}
/*自動演奏のクリック*/
autoPlayBtn.click(()=>{
    if(isAutoPlaying==0){
        startAutoPlay();
    }
    //自動演奏を止める
    else if(isAutoPlaying==1){
        stopAutoPlay();
    }
});
/*音を出す*/
for(let i=0; i<7;i++){
    playNoteFns[i]=(playTimerCount)=>{
        $(noteSelectors[i]).css('background','black');
        $(noteSelectors[i]).css('color','white');
        $(noteSelectors[i]).css('outline','2px white solid');
        if(!audio)audio=new AudioContext();
        if(audio.state==="suspended")audio.resume();
        let oscillator=audio.createOscillator();
        let gain=audio.createGain();
        oscillator.connect(gain);
        gain.connect(audio.destination);
        oscillator.type="square";
        gain.gain.value=volumeSlider.value;
        oscillator.frequency.value=sound_frequency[i];  
        oscillator.start();
        //保存
        active_audio[i]=oscillator;
    
        setTimeout(()=>{
            oscillator.stop();
            stopNoteFns[i]();
        },playTimerCount);
    }
}
/*音を出す・シャープ*/
for(let i=7; i<12;i++){
    playNoteFns[i]=(playTimerCount)=>{
        $(noteSelectors[i]).css('background','white');
        $(noteSelectors[i]).css('color','black');
        $(noteSelectors[i]).css('outline','2px black solid');
        if(!audio)audio=new AudioContext();
        if(audio.state==="suspended")audio.resume();
        let oscillator=audio.createOscillator();
        let gain=audio.createGain();
        oscillator.connect(gain);
        gain.connect(audio.destination);
        oscillator.type="square";
        gain.gain.value=volumeSlider.value;
        oscillator.frequency.value=sound_frequency[i];  
        oscillator.start();
        
        //保存
        active_audio[i]=oscillator;

        setTimeout(()=>{
            oscillator.stop();
            stopNoteFns[i]();
        },playTimerCount);
    }
}
/*音を止める*/
for(let i=0; i<7;i++){
    stopNoteFns[i]=()=>{
        //音が1つだけなっている時
        if(activeNoteCount==1){
            activeNoteCount=0;
            noteDisplay.text('音名');
        }
        //音が複数なっている時
        else if(activeNoteCount>1){
            activeNoteCount--;
        }
        $(noteSelectors[i]).css('background','white');
        $(noteSelectors[i]).css('color','black');
        $(noteSelectors[i]).css('outline','2px black solid');
    }
}
/*音を止める・シャープ*/
for(let i=7; i<12;i++){
    stopNoteFns[i]=()=>{
        //音が1つだけなっている時
        if(activeNoteCount==1){
            activeNoteCount=0;
            noteDisplay.text('音名');
        }
        //音が複数なっている時
        else if(activeNoteCount>1){
            activeNoteCount--;
        }
        $(noteSelectors[i]).css('background','black');
        $(noteSelectors[i]).css('color','white');
        $(noteSelectors[i]).css('outline','2px black solid');
    }
}
/*演奏の終わり*/
stopAutoPlay=()=>{  
    noteDisplay.text('音名');
    autoPlayBtn.text('自動演奏');
    //タイマーの初期化
    clearInterval(autoPlayIntervalId);
    playTimerCount=0;
    isAutoPlaying=0;
}
/*自動演奏の処理*/
startAutoPlay=()=>{
    isAutoPlaying=1;
    autoPlayBtn.text('演奏中止');
    //乱数生成
    let random=Math.random() * 100;
    //きらきら星
    if(random<=50){
        autoPlayIntervalId=setInterval(playTwinkleStar,100);                
    }
    //チューリップ
    else{
        autoPlayIntervalId = setInterval(playTulip,100);
    }
}
/*きらきら星・譜面*/
playTwinkleStar=()=>{
    noteDisplay.text('自動演奏中です：きらきら星');
    playTimerCount++;
    if(playTimerCount==5||playTimerCount==15||playTimerCount==330||playTimerCount==340){playNoteFns[0](500);}
    else if(playTimerCount==150||playTimerCount==470){playNoteFns[0](2000);}
    else if(playTimerCount==130||playTimerCount==140||playTimerCount==450||playTimerCount==460){playNoteFns[1](500);}
    else if(playTimerCount==230||playTimerCount==310){playNoteFns[1](2000);}
    else if(playTimerCount==110||playTimerCount==120||playTimerCount==210||playTimerCount==220||playTimerCount==290||playTimerCount==300||playTimerCount==430||playTimerCount==440||playTimerCount==290){playNoteFns[2](500);}
    else if(playTimerCount==90||playTimerCount==100||playTimerCount==190||playTimerCount==200||playTimerCount==270||playTimerCount==280||playTimerCount==410||playTimerCount==420){playNoteFns[3](500);}
    else if(playTimerCount==25||playTimerCount==35||playTimerCount==170||playTimerCount==180||playTimerCount==250||playTimerCount==260||playTimerCount==350||playTimerCount==360){playNoteFns[4](500);}
    else if(playTimerCount==65||playTimerCount==390){playNoteFns[4](2000);}
    else if(playTimerCount==45||playTimerCount==55||playTimerCount==370||playTimerCount==380){playNoteFns[5](500);}
    else if(playTimerCount==490){stopAutoPlay();}
}
/*チューリップ・譜面*/
playTulip=()=>{
    noteDisplay.text('自動演奏中です：チューリップ');
    playTimerCount++;
    if(playTimerCount==5||playTimerCount==25||playTimerCount==60||playTimerCount==85||playTimerCount==105||playTimerCount==140){playNoteFns[0](500);}
    else if(playTimerCount==155){playNoteFns[0](1000);}
    else if(playTimerCount==10||playTimerCount==30||playTimerCount==55||playTimerCount==65||playTimerCount==90||playTimerCount==110||playTimerCount==135||playTimerCount==145){playNoteFns[1](500);}
    else if(playTimerCount==225){playNoteFns[0](4000);}
    else if(playTimerCount==215||playTimerCount==220){playNoteFns[1](250);}
    else if(playTimerCount==75){playNoteFns[1](1000);}
    else if(playTimerCount==175||playTimerCount==205||playTimerCount==210){playNoteFns[2](250);}
    else if(playTimerCount==50||playTimerCount==70||playTimerCount==130||playTimerCount==150){playNoteFns[2](500);}
    else if(playTimerCount==15||playTimerCount==35||playTimerCount==95||playTimerCount==115){playNoteFns[2](1000);}
    else if(playTimerCount==165||playTimerCount==170||playTimerCount==180){playNoteFns[4](250);}
    else if(playTimerCount==45||playTimerCount==125){playNoteFns[4](500);}
    else if(playTimerCount==195){playNoteFns[4](1000);}
    else if(playTimerCount==185||playTimerCount==190){playNoteFns[5](250);}
    else if(playTimerCount==265){stopAutoPlay();}
}
//キーボタン関数
const onKeyboardInputDown=(k)=>{
    let sound_name = keyBindings[k.code];
    if(sound_name===undefined)return;
    if(isAutoPlaying==0&&press_key[sound_name]==false){
        press_key[sound_name]=true;
        activeNoteCount++;
        noteDisplay.text(noteLabels[sound_name]);
        playNoteFns[sound_name](10000);
        press_key[sound_name]=true;
    }
}
//キーボタン関数
const onKeyboardInputUp=(k)=>{
    let sound_name = keyBindings[k.code];
    let active_sound = active_audio[sound_name];

    if(sound_name===undefined)return;
    if(!active_sound)return;
    
    //oscillator.stop();
    if(isAutoPlaying==0&&press_key[sound_name]==true){
        activeNoteCount++;
        noteDisplay.text(noteLabels[sound_name]);
        //setTimeout(()=>active_sound.stop(),100);
        active_sound.stop();
        press_key[sound_name]=false;
        stopNoteFns[sound_name]();
        noteDisplay.text('音名');
    }
}

//キーボタン押し込み
document.addEventListener('keydown', onKeyboardInputDown);
//キーボタン離し
document.addEventListener('keyup', onKeyboardInputUp);