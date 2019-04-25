#define trigPin 0
#define echoPin 1

#include <SPI.h>
#include <WiFiNINA.h>

char ssid[] = "Grant's iPhone";
char pass[] = "password";

int status = WL_IDLE_STATUS;
char server[] = "people.eecs.ku.edu";

WiFiSSLClient client;

void setup() {
  pinMode(trigPin, OUTPUT);
  pinMode(echoPin, INPUT);
  
  //Initialize serial interface
  Serial.begin(9600);

  // check for the WiFi module:
  if (WiFi.status() == WL_NO_MODULE) {
    Serial.println("Communication with WiFi module failed!");
    // don't continue
    while (true);
  }

  String fv = WiFi.firmwareVersion();
  if (fv < "1.0.0") {
    Serial.println("Please upgrade the firmware");
  }

  // attempt to connect to Wifi network:
  while (status != WL_CONNECTED) {
    Serial.print("Attempting to connect to SSID: ");
    Serial.println(ssid);
    status = WiFi.begin(ssid, pass);

    // wait 10 seconds for connection:
    delay(10000);
  }
  Serial.println("Connected to wifi");
  printWifiStatus();

  // Setup Code for distance
  delay(30000); // Wait 30 seconds to ensure mounting is complete
  Serial.println("Starting initial distance calibration");
  if (client.connect(server, 443)) {
    Serial.println("Connecting to server");
    String URL = "GET /~m326s072/EECS-388-Project/submitData.php?setup=";
    String Distance = String(getDistance());
    Serial.println(URL + Distance);
    client.println(URL + Distance);
    client.print("Host: ");
    client.println(server);
    client.println("Connection: close");
    client.println();
  }

}

void loop() {
  Serial.println("\nStarting connection to server...");
  if (client.connect(server, 443)) {

    Serial.println("connected to server");
    // Make a HTTP request:
    String URL = "GET /~m326s072/EECS-388-Project/submitData.php?distance=";
    String Distance = String(getDistance());
    Serial.println(URL + Distance);
    client.println(URL + Distance);
    client.print("Host: ");
    client.println(server);
    client.println("Connection: close");
    client.println();
  }

  // if there are incoming bytes available
  // from the server, read them and print them:
  while (client.available()) {
  char c = client.read();
  Serial.write(c);
  }
  
  // if the server's disconnected, stop the client:
  if (!client.connected()) {
    Serial.println();
    Serial.println("disconnecting from server.");
    client.stop();
  }
  // Wait 30 seconds before reading again
  delay(30000);
}


void printWifiStatus() {
  // print the SSID of the network you're attached to:
  Serial.print("SSID: ");
  Serial.println(WiFi.SSID());

  // print your board's IP address:
  IPAddress ip = WiFi.localIP();
  Serial.print("IP Address: ");
  Serial.println(ip);

  // print the received signal strength:
  long rssi = WiFi.RSSI();
  Serial.print("signal strength (RSSI):");
  Serial.print(rssi);
  Serial.println(" dBm");
}

int getDistance(){
  int* distances = new int[11];
  for (int i = 0; i < 11; i++){
    long duration, distance;
    digitalWrite(trigPin, LOW);
    delayMicroseconds(2);
    digitalWrite(trigPin, HIGH);
    delayMicroseconds(10);
    digitalWrite(trigPin, LOW);
    duration = pulseIn(echoPin, HIGH);
    distance = (duration/2) / 29.1;
    Serial.println(distance);
    distances[i] = distance;
    delay(100);
  }
  bubbleSort(distances, 11);
  long distance = distances[5];
  delete[] distances;
  return distance;
}

void bubbleSort(int arr[], int n) 
{ 
   int i, j; 
   for (i = 0; i < n-1; i++)       

     // Last i elements are already in place    
     for (j = 0; j < n-i-1; j++)  
         if (arr[j] > arr[j+1]) 
            swap(&arr[j], &arr[j+1]); 
} 

void swap(int *xp, int *yp) 
{ 
    int temp = *xp; 
    *xp = *yp; 
    *yp = temp; 
} 
