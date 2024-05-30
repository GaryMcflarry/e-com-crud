import { Injectable } from '@angular/core';

@Injectable({
  providedIn: 'root'
})
export class StoreService {

//Function to set token for website
  setItem(key: string, value : string) {
    localStorage.setItem(key, value)
  }

  //Function to get token from website
  getItem(key: string) {
    return localStorage.getItem(key)
  }

  //Funtion to remive token from website
  removeItem(key : string) {
    localStorage.removeItem(key);
  }
}
