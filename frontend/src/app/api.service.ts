import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable, map } from 'rxjs';
import { Product } from './shared/types/product';
import { environment } from '../environments/environment';

@Injectable({
  providedIn: 'root'
})

@Injectable()
export class ApiService {

  constructor(private http:HttpClient) { }
    public getProducts () : Observable<Product[]> {
        return this.http.get<Product[]>(environment.backendClient);
    }

    public getCategories () : Observable<string[]> {
      return this.http.get<Product[]>(environment.backendClient).pipe(
        map((products: any[]) => {
          const categoriesSet = new Set<string>();
          products.forEach(product => {
            categoriesSet.add(product.category);
          });
          return Array.from(categoriesSet);
        })
      );
    }
  }