import { Component } from '@angular/core';
import { HUDService } from 'src/app/services/hud.service';
import { BehaviorSubject, combineLatest, map, switchMap } from 'rxjs';
import { WebRequestService } from 'src/app/services/web-request.service';
import { UserService } from 'src/app/services/user.service';
import { ActivatedRoute, Router } from '@angular/router';

@Component({
  selector: 'app-home',
  templateUrl: './home.component.html',
  styleUrls: ['./home.component.css'],
})
export class HomeComponent {
  //allows for importing other ts files and library features for component use
  constructor(
    public hud: HUDService,
    private web: WebRequestService,
    private U: UserService,
    private activated: ActivatedRoute,
    private router: Router
  ) {}

  //Observable to capture current product ID for popup component to make use of it
  params$ = this.activated.params.pipe(
    map((data: any) => (Object.keys(data).length > 0 ? data : null))
  );

  //Behavior subject to capture tthe branch ID of the user for other Obervables to make use of
  userBranch$ = new BehaviorSubject<any>('');

  //Observable to capture products data based of obtained branch
  products$ = this.userBranch$.pipe(
    // Use a switchMap to switch to a new observable
    switchMap((data: any) => {
      return this.web.get(`products/branch/${data}`).pipe();
    })
  );

  //Observable to capture user's branch data
  branch$ = this.userBranch$.pipe(
    // Use a switchMap to switch to a new observable
    switchMap((data: any) => {
      return this.web.post('branch', { branchID: data }).pipe();
    })
  );

  //Observable to obtian the user's current wishlist
  wishProducts$ = this.web
    .post('wishlist/products', { userID: this.U.getToken().userID })
    .pipe();

  //Observable to combine four other Observables together to subscribe to in the html code
  vm$ = combineLatest([
    this.products$,
    this.branch$,
    this.params$,
    this.wishProducts$,
  ]).pipe(
    map(([products, branch, params, wishProducts]: any) => {
      return { products, branch, params, wishProducts };
    })
  );

  /* ================================================================================= */

  //Allows for button navigation to titles on the home page
  navigateTo(element: HTMLHeadingElement) {
    element.scrollIntoView({
      behavior: 'smooth',
      block: 'start',
      inline: 'start',
    });
  }

  //Routing to base home page from the deal page
  routeToBase() {
    this.router.navigate(['main/home/1']);
  }

  //Allows for routing to specified deal page
  routeToDeals(type: string) {
    this.hud.deal = type;
    this.router.navigate([`main/home/deals/${this.hud.deal}/1`]);
  }

  //function to add wish item into users wishlist, sent to hud service function
  addWish(id: string) {
    const obj = {
      userID: this.U.getToken().userID,
      productID: id,
    };
    this.hud.addWishItem(obj);
  }

  //function to remove wish item into users wishlist, sent to hud service function
  removeWish(id: string) {
    const obj = {
      userID: this.U.getToken().userID,
      productID: id,
    };
    this.hud.removeWishItem(obj);
  }

  //Function to check if current product id is in the wishlist table
  wishCheck(id: any, wishlist: any[]) {
    return wishlist.some((item) => item.productID === id);
  }

  /* ================================================================================= */

  // function that occurs every time the component is loaded (navigation or refresing)
  ngOnInit() {
    this.userBranch$.next(this.U.getToken().branchID);
  }
}
