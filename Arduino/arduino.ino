#include <Servo.h> 
 
char input[4]; // [0]:Door No. [1][2]Should be 1 and 1. [3]:Open:1/Close:0
int n = 0; 
Servo myservo[4];  // create servo object to control a servo 
int pos[4] = {0,0,0,0} ; 
int doorAngle = 100;
int closeAngle = 10;
 
void setup() 
{ 
  Serial.begin(9600);
  myservo[1].attach(9);
  myservo[2].attach(8);   // attaches the servo on pin 9 to the servo object 
  CloseDoor(1);
  CloseDoor(2);
} 
 
 
void loop() 
{ 
  while(Serial.available() && n < 4){
    input[n] = Serial.read();
    n++;
  }
  if (n == 4) 
    CheckResult();
} 

void OpenDoor(int door){
     
    Serial.println("Door Opening...");
    for(; pos[door-48] < doorAngle; pos[door-48] += 1) {
      myservo[door-48].write(pos[door-48]);
      delay(15);
    } 
    delay(1000);
}
void CloseDoor(int door){
    Serial.println("Close..");
    for(; pos[door-48] > closeAngle ; pos[door-48] -= 1) {
      myservo[door-48].write(pos[door-48]);
      delay(15);   
    }
    delay(1000);   
    
}
void CheckResult(){
  n = 0;
  if (input[1]=='0' && input[2]=='0' && (input[0]=='1' || input[0]=='2') && (input[3]=='0' || input[3]=='1')) {
      Serial.println("GetMessage");
      if (input[3]=='0') CloseDoor(int(input[0]));
      else OpenDoor (int(input[0]));
      EmptyInput();
  } else {
    Serial.println("Unknown Input");
  }
}
void EmptyInput() {
  input[0]='z';
  input[1]='z';
  input[2]='z';
  input[3]='z';
}
