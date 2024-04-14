import { Component } from '@angular/core';
import { Observable, combineLatest, map, startWith } from 'rxjs';
import { ApiService } from '../../api.service';
import { Product } from '../../shared/types/product';
import { CommonModule } from '@angular/common';
import { FormBuilder, ReactiveFormsModule } from '@angular/forms';
import { AjouterProduit } from '../../shared/states/panier-state';
import { Store } from '@ngxs/store';

@Component({
  selector: 'app-liste-product',
  standalone: true,
  imports: [CommonModule, ReactiveFormsModule],
  templateUrl: './liste-product.component.html',
  styleUrl: './liste-product.component.css'
})
export class ListeProductComponent {
  
  searchForm = this.fb.group({
    nom: [''],
    categorie: [''],
  });

  products$!: Observable<Product[]>;
  categories$!: Observable<string[]>;

  constructor(private apiService: ApiService, private fb:FormBuilder, private store: Store) {
    this.products$ = this.getProducts();
    this.categories$ = this.apiService.getCategories();
  }
  
  private getProducts(): Observable<Product[]> {
    const products$ = this.apiService.getProducts();
    const recherche$ = this.searchForm.valueChanges.pipe(startWith(this.searchForm.value));

    return combineLatest(products$, recherche$).pipe(
      map(([products, { nom, categorie}]) => {
        return products.filter(product => {
          const nomDescription = product.name.toLowerCase() + product.description.toLowerCase();
          return (!nom || nomDescription.includes(nom.toLowerCase())) && (!categorie || product.category === categorie);
      });
    }));
  }

  triCroissant(): void {
    this.products$ = this.products$.pipe(
      map(products => {
        return products.sort((a, b) => a.price - b.price);
      })
    );
  }


  ajouterAuPanier(produit: Product) {
    this.store.dispatch(new AjouterProduit(produit));
  }
  
}
