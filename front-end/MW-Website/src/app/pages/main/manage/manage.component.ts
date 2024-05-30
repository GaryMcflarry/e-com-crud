import { Component } from '@angular/core';
import { MatDialog } from '@angular/material/dialog';
import {
  BehaviorSubject,
  Subject,
  combineLatest,
  debounceTime,
  map,
  switchMap,
} from 'rxjs';
import { HUDService } from 'src/app/services/hud.service';
import { UserService } from 'src/app/services/user.service';
import { WebRequestService } from 'src/app/services/web-request.service';
import { SignupComponent } from '../../auth/signup/signup.component';

@Component({
  selector: 'app-manage',
  templateUrl: './manage.component.html',
  styleUrls: ['./manage.component.css'],
})
export class ManageComponent {

  //allows for importing other ts files and library features for component use
  constructor(
    private web: WebRequestService,
    private user: UserService,
    private hud: HUDService,
    public dialog: MatDialog
  ) {}

  //Behavior subjects to capture product data that will be used to update it for other Obervables to make use of
  input$ = new BehaviorSubject('');
  private priceObs = new Subject<any>();
  private priceChange: any;
  private quantityObs = new Subject<any>();
  private quantityChange: any;
  private discountObs = new Subject<any>();
  private discountChange: any;

  //Observable to capture products data based of obtained branch
  products$ = this.input$.asObservable().pipe(
    // Use a switchMap to switch to a new observable
    switchMap((data: any) => {
      return this.web.get(`products/branch/${data.branchID}`).pipe(
        map((response: any) => {
          return response.data2;
        }),
      );
    })
  );

  //Observable to capture users data based of obtained branch
  users$ = this.input$.asObservable().pipe(
    // Use a switchMap to switch to a new observable
    switchMap((data: any) => {
      return this.web.get(`users/branch/${data.branchID}`).pipe(
        map((response: any) => {
          return response.data;
        }),
      );
    })
  );

  //Observable to capture orders data based of obtained branch
  orders$ = this.input$.asObservable().pipe(
    // Use a switchMap to switch to a new observable
    switchMap((data: any) => {
      return this.web.get(`orders/branch/${data.branchID}`).pipe(
        map((response: any) => {
          if (response !== null) {
            return response.data;
          } else {
            return null;
          }
        }),
      );
    })
  );

  //Observable to combine three other Observables together to subscribe to in the html code
  vm$ = combineLatest([this.products$, this.users$, this.orders$]).pipe(
    //Obs that is taking two obs and putting them into an array, making things more ordered for console.log
    map(([products, users, orders]: any) => {
      return {
        products,
        users,
        orders,
      };
    })
  );

  /* ================================================================================= */

   //Function to populate Behavior Subject for Obs to use
   onPriceChange(product: any) {
    this.priceObs.next(product);
  }
  //Function to populate Behavior Subject for Obs to use
  onDiscountChange(product: any) {
    this.discountObs.next(product);
  }
  //Function to populate Behavior Subject for Obs to use
  onQuantityChange(product: any) {
    this.quantityObs.next(product);
  }

  //Function to update price of the project
  editPrice(price: any, ID: any) {
    const obj = {
      productID: ID,
      price: price,
    };
    this.hud.editProduct(obj);
  }
  //Function to update quantity of the project
  editQuantity(quantity: any, ID: any) {
    const obj = {
      productID: ID,
      quantity: quantity,
    };
    this.hud.editProduct(obj);
  }
  //Function to update discount of the project
  editDiscount(discount: any, ID: any) {
    const obj = {
      productID: ID,
      discount: discount,
    };
    this.hud.editProduct(obj);
  }

  //Function to remove spesific order
  removeOrder(ID: any) {
    this.hud.removeOrders(ID);
  }
  //Function to remove spesific user 
  removeUser(ID: any) {
    this.hud.removeUser(ID);
  }

  //Funtion to open add admin component dialog
  openDialog(
    enterAnimationDuration: string,
    exitAnimationDuration: string
  ): void {
    this.dialog.open(SignupComponent, {
      enterAnimationDuration,
      exitAnimationDuration,
    });
  }

  /* ================================================================================= */

  // function that occurs every time the component is loaded (navigation or refresing)
  ngOnInit() {
    this.input$.next(this.user.getToken());

    this.priceChange = this.priceObs
      .pipe(
        debounceTime(1000) // Wait for 1 second after the user stops typing
      )
      .subscribe((data) => this.editPrice(data.price, data.productID)); //Subscribe to Obs to input into other function

    this.quantityChange = this.quantityObs
      .pipe(
        debounceTime(1000) // Wait for 1 second after the user stops typing
      )
      .subscribe((data) => this.editQuantity(data.quantity, data.productID)); //Subscribe to Obs to input into other function

    this.discountChange = this.discountObs
      .pipe(
        debounceTime(1000) // Wait for 1 second after the user stops typing
      )
      .subscribe((data) => this.editDiscount(data.discount, data.productID)); //Subscribe to Obs to input into other function
  }

  // function that occurs every time the component is done with (when a user goes to another page etc)
  ngOnDestroy() {
    //Unsubscribing from variable
    if (this.priceChange) {
      this.priceChange.unsubscribe();
    }
    //Unsubscribing from variable
    if (this.discountChange) {
      this.discountChange.unsubscribe();
    }
    //Unsubscribing from variable
    if (this.quantityChange) {
      this.quantityChange.unsubscribe();
    }
  }
}
