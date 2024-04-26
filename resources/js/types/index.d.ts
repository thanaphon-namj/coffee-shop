export interface Order {
  OrderID: number;
  Total: number;
  Payment: string;
  PurchaseDate: string;
}

export interface Product {
  ProductID: number;
  ProductName: string;
  Description?: string;
  Category: string;
  Price: number;
  Stock: number;
  ImageUrl: string;
}

export interface Customer {
  CustomerID: number;
  FirstName: string;
  LastName: string;
  BirthDate: string;
  PhoneNo: string;
  Points: number;
  RegisterDate: string;
}

export interface Employee {
  EmployeeID: number;
  FirstName: string;
  LastName: string;
  BirthDate: string;
  PhoneNo: string;
  Email: string;
  Position: string;
  Salary: number;
  StartDate: string;
  EndDate: string;
}

export type PageProps<
  T extends Record<string, unknown> = Record<string, unknown>,
> = T & {
  employee: Employee;
};
