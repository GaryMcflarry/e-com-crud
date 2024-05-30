import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { MainComponent } from './main.component';
import { HomeComponent } from './home/home.component';
import { MainRoutingModule } from './main-routing.module';
import { MatIconModule } from '@angular/material/icon';
import { LayoutModule } from '@angular/cdk/layout';
import { HUDService } from 'src/app/services/hud.service';
import { CardComponent } from './home/card/card.component';
import { MatSidenavModule } from '@angular/material/sidenav';
import { CheckoutComponent } from './checkout/checkout.component';
import { PopupComponent } from './home/popup/popup.component';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';

//Components connected to Module
@NgModule({
  declarations: [MainComponent, HomeComponent, CardComponent, CheckoutComponent, PopupComponent],

  //All libraries, tools etc that the connected components make use of
  imports: [
    CommonModule,
    MainRoutingModule,
    MatIconModule,
    LayoutModule,
    MatSidenavModule,
    ReactiveFormsModule,
    FormsModule,
  ],
  providers: [
    HUDService, // Provide HUDService here
  ],
})
export class MainModule {}
