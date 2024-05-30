import { Component } from '@angular/core';
import { Router } from '@angular/router';
import { BehaviorSubject, map, switchMap, tap } from 'rxjs';
import { HUDService } from 'src/app/services/hud.service';
import { UserService } from 'src/app/services/user.service';
import { WebRequestService } from 'src/app/services/web-request.service';

@Component({
  selector: 'app-popup',
  templateUrl: './popup.component.html',
  styleUrls: ['./popup.component.css'],
})

//allows for importing other ts files and library features for component use
export class PopupComponent {
  constructor(
    private router: Router,
    private web: WebRequestService,
    private hud: HUDService,
    private user: UserService
  ) {}

  //Variables to get current url and strings within it
  url = window.location.href;
  parts = this.url.split('/');
  input!: string;

  /* ================================================================================= */

  //Behavior subject to capture product id for popup component to make use of
  ID$ = new BehaviorSubject<any>('');

  //Observable to get product data and if it has a discount calculated it's new price
  product$ = this.ID$.asObservable().pipe(
    // Use a switchMap to switch to a new observable
    switchMap((data: any) => {
      return this.web.post('product', { productID: data }).pipe(
        map((data: any) => {
          data.data.newPrice = data.data.price * (1 - data.data.discount / 100);
          return data;
        })
      );
    })
  );

  /* ================================================================================= */

  //Check to make sure that user is in the deals page
  isDealsRoute() {
    return this.router.url.includes('deals');
  }

  //Function to allow user to add product to their cart
  addToCart(id: any) {
    const cartObj = {
      userID: this.user.getToken().userID,
      productID: id,
    };
    this.hud.addCartItem(cartObj);
  }

  /* ================================================================================= */

  //function that occurs every time the component is loaded (navigation or refresing)
  //Gathering the ID of the product from the URL to give to the behavior subject
  ngOnInit() {
    if (this.isDealsRoute()) {
      this.input = this.parts[7];
      this.ID$.next(this.input);
    } else {
      this.input = this.parts[5];
      this.ID$.next(this.input);
    }
  }
}
