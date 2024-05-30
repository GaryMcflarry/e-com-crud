import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { ProfileRoutingModule } from './profile-routing.module';
import { ProfileComponent } from './profile.component';
import { AccordionModule } from 'primeng/accordion';
import { UpdateAccComponent } from './update-acc/update-acc.component';
import { WishlistComponent } from './wishlist/wishlist.component';
import {MatDialogModule} from '@angular/material/dialog';
import { FormsModule } from '@angular/forms';
@NgModule({
//Components connected to Module
  declarations: [
    ProfileComponent,
    UpdateAccComponent,
    WishlistComponent
  ],
  //All libraries, tools etc that the connected components make use of
  imports: [
    CommonModule,
    ProfileRoutingModule,
    AccordionModule,
    MatDialogModule,
    FormsModule,

  ]
})
export class ProfileModule { }
