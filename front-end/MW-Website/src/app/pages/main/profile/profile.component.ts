import { Component } from '@angular/core';
import { MatDialog } from '@angular/material/dialog';
import { UpdateAccComponent } from './update-acc/update-acc.component';
import { WishlistComponent } from './wishlist/wishlist.component';
import { UserService } from 'src/app/services/user.service';
import { Router } from '@angular/router';

@Component({
  selector: 'app-profile',
  templateUrl: './profile.component.html',
  styleUrls: ['./profile.component.css'],
})
export class ProfileComponent {

  //allows for importing other ts files and library features for component use
  constructor(
    public dialog: MatDialog,
    public user: UserService,
    private router: Router
  ) {}

  
  //Varialbes for getting the first letter of the user account
  iconName: any;
  admin = this.user.getToken().admin;

  /* ================================================================================= */

  //To route to the home page
  routeToHome() {
    this.router.navigate(['main/home']);
  }

  //To open the update account dialog
  openDialog(
    enterAnimationDuration: string,
    exitAnimationDuration: string
  ): void {
    this.dialog.open(UpdateAccComponent, {
      enterAnimationDuration,
      exitAnimationDuration,
    });
  }

  //To open the wishlist dialog
  wishList(): void {
    const dialogRef = this.dialog.open(WishlistComponent, {
    });
    dialogRef.afterClosed().subscribe((result) => {
    });
  }

  /* ================================================================================= */

  // function that occurs every time the component is loaded (navigation or refresing)
  ngOnInit() {
    this.iconName = this.user.getToken().username.charAt(0);
    //console.log(this.iconName)
  }
}
