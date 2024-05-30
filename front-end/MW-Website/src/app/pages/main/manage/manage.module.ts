import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ManageRoutingModule } from './manage-routing.module';
import { ManageComponent } from './manage.component';
import { MatTabsModule } from '@angular/material/tabs';
import { FormsModule } from '@angular/forms';

@NgModule({
  //Components connected to Module
  declarations: [ManageComponent],
  //All libraries, tools etc that the connected components make use of
  imports: [FormsModule, CommonModule, ManageRoutingModule, MatTabsModule],
})
export class ManageModule {}
