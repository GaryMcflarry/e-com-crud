import { Component } from '@angular/core';
import { FormControl } from '@angular/forms';
import { Router } from '@angular/router';
import { debounce, map, switchMap, timer } from 'rxjs';
import { HUDService } from 'src/app/services/hud.service';
import { UserService } from 'src/app/services/user.service';

@Component({
  selector: 'app-main',
  templateUrl: './main.component.html',
  styleUrls: ['./main.component.css'],
})
export class MainComponent {
  //allows for importing other ts files and library features for component use
  constructor(
    public hud: HUDService,
    private router: Router,
    private user: UserService
  ) {}

  //Variable for seatch bar input
  searchText = new FormControl(
    '',
  );

  //Variable fo profile name and admin
  iconName: any;
  admin = this.user.getToken().admin;


  searchResults = this.searchText.valueChanges.pipe(
    //wait for 0.1 seconds after input
    debounce(() => timer(100)),
    // Use a switchMap to switch to a new observable
    switchMap((data: any) => {
      return this.hud.searchProducts_(data).pipe(
        map((data: any) => {
          console.log(data);
          if (data.data.length === 0) {
            return {
              data: data,
              error: true,
              message: 'Cannot find Product',
            };
          } else {
            return {
              data: data,
              error: false,
              message: 'Products Found!!!',
            };
          }
        })
      );
    }),
  );


  //Allowing for logging off 
  logOff() {
    this.router.navigate(['auth']);
    this.hud.logOff();
  }

  //Route to manage page
  manage() {
    this.router.navigate(['main/manage']);
  }

  //Route to check out page
  checkout() {
    this.router.navigate(['main/checkout']);
  }

  //route to home page
  home() {
    this.router.navigate(['main/home/1']);
  }

  //route to account page
  account() {
    this.router.navigate(['main/profile']);
  }

  //To clear the search bar
  clearSearch() {
    this.searchText.setValue('');
  }

  // function that occurs every time the component is loaded (navigation or refresing)
  ngOnInit() {
    this.iconName = this.user.getToken().username.charAt(0);
  }
}
