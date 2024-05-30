import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';

@Injectable({
  providedIn: 'root'
})
export class WebRequestService {

   //Variable to house the backend URL
   readonly ROOT_URL: any;

  //For Allocating the backend HTTP URL
  constructor(private http: HttpClient) { 
    //localhost testing
    // this.ROOT_URL = 'http://localhost/BACK-END/hud.php';  
    //hosting site url
    this.ROOT_URL = 'https://www.garymcflarry.store/hud.php'
  }

  //Function for POST HTTP requests
  post(uri: string, payload: object) {
    return this.http.post(`${this.ROOT_URL}/${uri}`, payload)
  }

  //Function for GET HTTP requests
  get(uri: string) {
    return this.http.get(`${this.ROOT_URL}/${uri}`)
  }

}
