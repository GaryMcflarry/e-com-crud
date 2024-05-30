import { Component } from '@angular/core';
import { format } from 'date-fns';
import { BehaviorSubject, forkJoin, map, switchMap, tap } from 'rxjs';
import { HUDService } from 'src/app/services/hud.service';
import { UserService } from 'src/app/services/user.service';
import { WebRequestService } from 'src/app/services/web-request.service';

@Component({
  selector: 'app-checkout',
  templateUrl: './checkout.component.html',
  styleUrls: ['./checkout.component.css'],
})
export class CheckoutComponent {
  //allows for importing other ts files and library features for component use
  constructor(
    private user: UserService,
    private web: WebRequestService,
    private hud: HUDService
  ) {}

  //Variables for array to keep track of order quantities
  //The index for the array
  //nullcheck to determine if the order quantity was not selected
  orderQuantity: Array<Number> = [];
  i = 0;
  nullCheck!: boolean;

  //Behavior subject to capture the user ID of the user for other Obervables to make use of
  user$ = new BehaviorSubject('');

  //Observable to gather product from the ID in the cart table that is related to the user ID
  userCart$ = this.user$.pipe(
    switchMap((userID: any) => {
      // Use a switchMap to switch to a new observable
      return this.web.post('user/cart', { userID }).pipe(
        switchMap((cartData: any) => {
          // Use switchMap to switch to a new observable for each product ID
          const productObservables = cartData.data.map((element: any) => {
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

  //Reactively calcualting total of spesific product and its order quantity
  totalCalculate(price: any, quantity: any) {
    const total = price * quantity;
    if (quantity != null) {
      return total;
    } else {
      return 0;
    }
  }

  //Taking array order quantities with the respective products to calculate total of entire cart
  getTotalAmount(cart: any[], index: number) {
    let total = 0;
    for (const product of cart) {
      total += this.totalCalculate(
        product.data.price,
        this.orderQuantity[index]
      );
      index++;
    }
    return total;
  }

  //Getting date to send to order table for every item that is checked out
  getFormattedDate(): string {
    const date = new Date();
    return format(date, 'yyyy-MM-dd HH:mm:ss');
  }

  checkOut() {
    this.i = 0; // Reset i at the start
    this.nullCheck = false; // Reset nullCheck at the start

    // Subscribing to Observble to see if any item has an undefined order quantity
    this.userCart$.subscribe((products: any[]) => {
      for (let i = 0; i < products.length; i++) {
        if (this.orderQuantity[i] === undefined) {
          this.nullCheck = true;
          break; // Exit the loop if any quantity is undefined
        }
      }

      //If any item has null order quantity
      if (this.nullCheck) {
        this.hud.messageService('warn', 'Warn', 'Please provide quantities');
        return; // Exit the method if there's an issue with quantities
      }

      //Creating object for every product in cart to send to the orders table
      products.forEach((product: any, index: number) => {
        const order = {
          branchID: this.user.getToken().branchID,
          userID: this.user.getToken().userID,
          orderProductID: product.data.productID,
          orderDate: this.getFormattedDate(),
          quantity: this.orderQuantity[index], // Use updated orderQuantity from HTML
        };

        this.hud.addOrders(order);
      });

      //Empty the user's cart
      this.hud.removeCart();
    });
  }

  //Allows user to remove a specific cart item
  removeCartItem(id: any) {
    this.hud.removeCartItem(id);
  }

  /* ================================================================================= */

  ngOnInit() {
    this.i = 0;
    this.user$.next(this.user.getToken().userID);
  }
}
