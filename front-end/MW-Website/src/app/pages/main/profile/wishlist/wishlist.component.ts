import { Component } from '@angular/core';
import { BehaviorSubject, forkJoin, map, switchMap, tap } from 'rxjs';
import { HUDService } from 'src/app/services/hud.service';
import { UserService } from 'src/app/services/user.service';
import { WebRequestService } from 'src/app/services/web-request.service';

@Component({
  selector: 'app-wishlist',
  templateUrl: './wishlist.component.html',
  styleUrls: ['./wishlist.component.css']
})
export class WishlistComponent {
  //allows for importing other ts files and library features for component use
  constructor(private user: UserService, private web: WebRequestService, private hud: HUDService) {}

  user$ = new BehaviorSubject('');

  userWish$ = this.user$.pipe(
    switchMap((userID: any) => {
      // Use a switchMap to switch to a new observable
      return this.web.post('wishlist/products', { userID }).pipe(
        switchMap((wishData: any) => {
          // Use switchMap to switch to a new observable for each product ID
          const productObservables = wishData.data.map((element: any) => {
            return this.web.post('product', element).pipe(
              map((data: any) => {
                return data;
              })
            );
          });
          // Use forkJoin to combine all product observables into a single observable
          return forkJoin(productObservables).pipe(
            tap((productDataArray: any) =>
              console.log('Product data array: ', productDataArray)
            )
          );
        })
      );
    })
  );

  /* ================================================================================= */
  
  //Funtion to remove specific item from wishlist
  removeWish(id : string) {
    const obj = {
      userID : this.user.getToken().userID,
      productID : id
    }
    this.hud.removeWishItem(obj);
  }

  //Funtion to allow user to add their wish item to their cart
  addToCart(id : any) {
    const cartObj = {
      userID: this.user.getToken().userID,
      productID: id,
    }
    this.hud.addCartItem(cartObj);
  }

  /* ================================================================================= */

  // function that occurs every time the component is loaded (navigation or refresing)
  ngOnInit() {
    this.user$.next(this.user.getToken().userID)
  }

}
