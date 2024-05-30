import { Component, Input } from '@angular/core';

@Component({
  selector: 'app-card',
  templateUrl: './card.component.html',
  styleUrls: ['./card.component.css'],
})
export class CardComponent {
  // Injected data from HTMl of Home component
  @Input() data!: any;
  newPrice: any;
  discountPercent: any;

  /* ================================================================================= */

  // function that occurs every time the component is loaded (navigation or refresing)
  ngOnInit() {
    if (this.data.discount !== null) {
      this.discountPercent = this.data.discount;
      // Convert the discount percentage to a decimal value
      const discountDecimal = this.discountPercent / 100;
      // Calculate the new price after discount
      this.newPrice = this.data.price * (1 - discountDecimal);
    }
  }
}
