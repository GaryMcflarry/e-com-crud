import { Injectable } from '@angular/core';
import { BreakpointObserver, Breakpoints } from '@angular/cdk/layout';
import { MessageService } from 'primeng/api';
import { WebRequestService } from './web-request.service';
import { catchError, map, throwError } from 'rxjs';
import { StoreService } from './store.service';
import { Router } from '@angular/router';
import { PopupComponent } from '../pages/main/home/popup/popup.component';
import { MatDialog } from '@angular/material/dialog';
import { UserService } from './user.service';

@Injectable({
  providedIn: 'root',
})
export class HUDService {
  //allows for importing other ts files and library features for component use
  constructor(
    private breakpointObserver: BreakpointObserver,
    private message: MessageService,
    private web: WebRequestService,
    private store: StoreService,
    public router: Router,
    public dialog: MatDialog,
    private user: UserService
  ) {
    //Observable for determining the current size of the screen and publishing variables that can be used
    //all over the website
    this.breakpointObserver
      .observe([
        Breakpoints.HandsetPortrait,
        Breakpoints.TabletPortrait,
        Breakpoints.WebPortrait,
        Breakpoints.HandsetLandscape,
        Breakpoints.TabletLandscape,
        Breakpoints.WebLandscape,
      ])
      .subscribe((result) => {
        this.mobileP = result.breakpoints[Breakpoints.HandsetPortrait];
        this.mobileL = result.breakpoints[Breakpoints.HandsetLandscape];
        this.tabletP = result.breakpoints[Breakpoints.TabletPortrait];
        this.tabletL = result.breakpoints[Breakpoints.TabletLandscape];
        this.desktopP = result.breakpoints[Breakpoints.WebPortrait];
        this.desktopL = result.breakpoints[Breakpoints.WebLandscape];

        if (
          this.tabletL == true ||
          this.desktopP == true ||
          this.desktopL == true
        ) {
          this.topBarBg = true;
          this.topBarSm = false;
        } else if (
          this.tabletP == true ||
          this.mobileP == true ||
          this.mobileL == true
        ) {
          this.topBarSm = true;
          this.topBarBg = false;
        }
      });
  }

  //Variable for breakpoint observables
  mobileP: boolean = false;
  mobileL: boolean = false;
  tabletP: boolean = false;
  tabletL: boolean = false;
  desktopP: boolean = false;
  desktopL: boolean = false;
  topBarBg: boolean = false;
  topBarSm: boolean = false;

  // Variable for obtaining the current URL
  url = window.location.href;
  parts = this.url.split('/');
  input = this.parts[6];
  deal: any;

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
    this.router.navigate([`main/home/${this.user.getToken().branchID}`]);
  }

  //route to account page
  account() {
    this.router.navigate(['main/profile']);
  }

  //Checking if in the deal page
  isDealsRoute() {
    return this.router.url.includes('deals');
  }

  //Checking if in the profile page
  isProfileRoute() {
    return this.router.url.includes('profie');
  }

  //Checking if in the check out page
  isCheckoutRoute() {
    return this.router.url.includes('checkout');
  }

  //Checking if in the manage page
  isManageRoute() {
    return this.router.url.includes('manage');
  }

  //Checking if in the home page
  isHomeRoute() {
    return this.router.url.includes('home');
  }

  //Open dialog for pop up that is used in the home and main component
  openDialog(
    enterAnimationDuration: string,
    exitAnimationDuration: string
  ): void {
    this.dialog.open(PopupComponent, {
      enterAnimationDuration,
      exitAnimationDuration,
    });
  }

  //Function to make use of the toast notification
  messageService(severity: any, summary: any, message: any) {
    this.message.add({
      severity: severity,
      summary: summary,
      detail: message,
    });
  }

  //Creating desired URL for pop up before it appears
  popUpRoute(id: string) {
    // this.urlCheck();
    if (this.isDealsRoute()) {
      this.router.navigate([`main/home/deals/${this.deal}/${id}`]);
    } else {
      this.router.navigate([`main/home/${id}`]);
    }
    setTimeout(() => {
      this.openDialog('0ms', '0ms');
    }, 500); // 500 milliseconds = 0.5 seconds
  }

  //Allowing the user to log of
  logOff() {
    this.store.removeItem('Token');
    this.router.navigate(['auth']);
  }

  //Calling backend for user Login
  login(object: any) {
    this.web
      .post('login', object)
      .pipe(
        catchError((response) => {
          this.message.add({
            severity: 'error',
            summary: 'Error',
            detail: response.error.message,
          });
          return throwError(response);
        })
      )
      .subscribe((response: any) => {
        this.message.add({
          severity: 'success',
          summary: 'Success',
          detail: response.message,
        });
        this.store.setItem('Token', response.token);
        this.router.navigate([`/main/home/${this.user.getToken().branchID}`]);
      });
  }

  //Calling backend for user sign up
  signup(object: any) {
    if (
      object.username !== '' &&
      object.password !== '' &&
      object.contact !== '' &&
      object.chosenBranch !== ''
    ) {
      this.web
        .post('user/signup', object)
        .pipe(
          catchError((response) => {
            this.message.add({
              severity: 'warn',
              summary: 'Warn',
              detail: response.error.message,
            });
            return throwError(response);
          })
        )
        .subscribe((response: any) => {
          this.message.add({
            severity: 'info',
            summary: 'Info',
            detail: response.message,
          });
        });
    } else {
      this.message.add({
        severity: 'warn',
        summary: 'Warn',
        detail: 'Input all fields!',
      });
    }
  }

  //Calling backend for editing user information
  updateUser(object: any) {
    if (
      object.username !== '' &&
      object.password !== '' &&
      object.contact !== ''
    ) {
      this.web
        .post('edit/user', object)
        .pipe(
          catchError((response) => {
            this.message.add({
              severity: 'warn',
              summary: 'Warn',
              detail: response.error.message,
            });
            return throwError(response);
          })
        )
        .subscribe((response: any) => {
          this.message.add({
            severity: 'info',
            summary: 'Info',
            detail: response.message,
          });
        });
    } else {
      this.message.add({
        severity: 'warn',
        summary: 'Warn',
        detail: 'Input all fields!',
      });
    }
  }

  //Calling backend to add item to users wish list
  addWishItem(object: any) {
    this.web
      .post('add/wish', object)
      .pipe(
        catchError((response) => {
          this.message.add({
            severity: 'warn',
            summary: 'Warn',
            detail: response.error.message,
          });
          return throwError(response);
        })
      )
      .subscribe((response: any) => {
        this.message.add({
          severity: 'info',
          summary: 'Info',
          detail: response.message,
        });
      });
  }

  //Calling backend to remove item for users wish list
  removeWishItem(object: any) {
    this.web
      .post('remove/wish', object)
      .pipe(
        catchError((response) => {
          this.message.add({
            severity: 'warn',
            summary: 'Warn',
            detail: response.error.message,
          });
          return throwError(response);
        })
      )
      .subscribe((response: any) => {
        this.message.add({
          severity: 'info',
          summary: 'Info',
          detail: response.message,
        });
      });
  }

  //Calling backend to search product table in database
  searchProducts_(info: any) {
    return this.web
      .post('search/products', {
        query: info,
        branchID: this.user.getToken().branchID,
      })
      .pipe(
        map((data: any) => {
          return data;
        })
      );
  }

  //Calling backend to add item to user cart
  addCartItem(object: any) {
    this.web
      .post('add/cart', object)
      .pipe(
        catchError((response) => {
          this.message.add({
            severity: 'warn',
            summary: 'Warn',
            detail: response.error.message,
          });
          return throwError(response);
        })
      )
      .subscribe((response: any) => {
        this.message.add({
          severity: 'info',
          summary: 'Info',
          detail: response.message,
        });
      });
  }

  //Calling backend to add orders to database
  addOrders(object: any) {
    this.web
      .post('user/order', object)
      .pipe(
        catchError((response) => {
          this.message.add({
            severity: 'warn',
            summary: 'Warn',
            detail: response.error.message,
          });
          return throwError(response);
        })
      )
      .subscribe((response: any) => {
        this.message.add({
          severity: 'info',
          summary: 'Info',
          detail: response.message,
        });
      });
  }

  //Calling backend to remove order from database
  removeOrders(ID: any) {
    this.web
      .post('remove/order', { orderID: ID })
      .pipe(
        catchError((response) => {
          this.message.add({
            severity: 'warn',
            summary: 'Warn',
            detail: response.error.message,
          });
          return throwError(response);
        })
      )
      .subscribe((response: any) => {
        this.message.add({
          severity: 'info',
          summary: 'Info',
          detail: response.message,
        });
      });
  }

  //Calling backend to remove user from database
  removeUser(ID: any) {
    this.web
      .post('user/remove', { userID: ID })
      .pipe(
        catchError((response) => {
          this.message.add({
            severity: 'warn',
            summary: 'Warn',
            detail: response.error.message,
          });
          return throwError(response);
        })
      )
      .subscribe((response: any) => {
        this.message.add({
          severity: 'info',
          summary: 'Info',
          detail: response.message,
        });
      });
  }

  //Calling backend to remove user cart
  removeCart() {
    this.web
      .post('remove/cart', { userID: this.user.getToken().userID })
      .pipe(
        catchError((response) => {
          this.message.add({
            severity: 'warn',
            summary: 'Warn',
            detail: response.error.message,
          });
          return throwError(response);
        })
      )
      .subscribe((response: any) => {
        this.message.add({
          severity: 'success',
          summary: 'Success',
          detail: response.message,
        });
        this.router.navigate(['main/home/1']);
      });
  }

  //Calling backend to remove item from user cart
  removeCartItem(id: any) {
    this.web
      .post('remove/cartItem', {
        userID: this.user.getToken().userID,
        productID: id,
      })
      .pipe(
        catchError((response) => {
          this.message.add({
            severity: 'warn',
            summary: 'Warn',
            detail: response.error.message,
          });
          return throwError(response);
        })
      )
      .subscribe((response: any) => {
        this.message.add({
          severity: 'info',
          summary: 'Info',
          detail: response.message,
        });
      });
  }

  //Calling backend to edit product information
  editProduct(object: any) {
    this.web
      .post('edit/product', object)
      .pipe(
        catchError((response) => {
          this.message.add({
            severity: 'warn',
            summary: 'Warn',
            detail: response.error.message,
          });
          return throwError(response);
        })
      )
      .subscribe((response: any) => {
        this.message.add({
          severity: 'info',
          summary: 'Info',
          detail: response.message,
        });
      });
  }
}
