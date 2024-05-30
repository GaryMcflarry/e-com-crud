import { Injectable } from '@angular/core';
import { jwtDecode } from 'jwt-decode';
import { StoreService } from './store.service';
import { BehaviorSubject, map} from 'rxjs';
@Injectable({
  providedIn: 'root',
})
export class UserService {
  //allows for importing other ts files and library features for component use
  constructor(private store: StoreService) {}

  //Behavior subject to capture tthe ID of the user for other Obervables to make use of
  private user$ = new BehaviorSubject<any>('Default');

  //Observable to capture products data based of obtained branch
  user = this.user$.asObservable().pipe(
    // tap((data: any) => console.log('user', data)),
    map((data: any) => {
      return {
        userID: data.userID,
        username: data.username,
        userBranch: data.branch,
        admin: data.admin,
      };
    }));

  //Function to get a decrypted version of the token
  getToken() {
    return this.decrypt(this.store.getItem('Token'));
  }

  //Function that decrypts the token
  decrypt(token: any) {
    return this.decipherToken(jwtDecode(token));
  }

  decipherToken(info: any) {
    return info;
  }

  //Funtion that will continuesly update current user information for website
  MaintainUser(token: any) {
    this.store.setItem('Token', token);
    this.user$.next(this.getToken());
  }

}
