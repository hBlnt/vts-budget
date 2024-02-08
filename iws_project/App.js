import React, { useState, useEffect } from 'react';
import { ActivityIndicator, View, Text, TextInput, Button, StyleSheet, Alert, FlatList, TouchableOpacity } from 'react-native';
import { NavigationContainer, useIsFocused } from '@react-navigation/native';
import { createNativeStackNavigator } from '@react-navigation/native-stack';
import AsyncStorage from '@react-native-async-storage/async-storage';
import { get } from 'react-native/Libraries/TurboModule/TurboModuleRegistry';

const SITE = 'https://budget.stud.vts.su.ac.rs/reactPHP/';

let asyncIdUser = null;


const Stack = createNativeStackNavigator();
// Store data
const storeData = async (key, value) => {
  try {
    await AsyncStorage.setItem(key, JSON.stringify(value));
    console.log('Data saved successfully');
  } catch (error) {
    console.error('Error saving data: ', error);
  }
};

// Get data
const getData = async (key) => {
  try {
    const value = await AsyncStorage.getItem(key);
    if (value !== null) {
      // Data found
      return JSON.parse(value);
    } else {
      // Data not found
      console.log('No data found for the key: ', key);
    }
  } catch (error) {
    console.error('Error retrieving data: ', error);
  }
};

// Remove data
const removeData = async (key) => {
  try {
    await AsyncStorage.removeItem(key);
    console.log('Data removed successfully');
  } catch (error) {
    console.error('Error removing data: ', error);
  }
};

const MyStack = () => {

  return (
    <NavigationContainer>
      <Stack.Navigator>
        <Stack.Screen
          name="Home"
          component={HomeScreen}
          options={{ title: 'Home' }}
        />
        <Stack.Screen
          name="AttractionData"
          component={AttractionDataScreen}
          options={{ title: 'Attraction data' }}
        />
        <Stack.Screen
          name="Login"
          component={LoginScreen}
          options={{ title: 'Log in' }}
        />
        <Stack.Screen
          name="Favourites"
          component={FavouritesScreen}
          options={{ title: 'Favourites' }}
        />
        <Stack.Screen
          name="UpdateUser"
          component={UpdateUserScreen}
          options={{ title: 'Update profile' }}
        />
      </Stack.Navigator>
    </NavigationContainer>
  );
};

const HomeScreen = ({ navigation }) => {
  const isFocused = useIsFocused();
  const [attractions, setAttractions] = useState([]);
  const [isLoading, setLoading] = useState(true);
  const [asyncIdUser, setAsyncIdUser] = useState(null);
  const [id, setId] = useState('');
  const [name, setName] = useState('');


  const renderItem = ({ item }) => (
    <View style={styles.item}>
      <Text style={styles.strong}>{item.attraction_name}</Text>
      <Text style={styles.data}>Type: {item.type}</Text>

      {asyncIdUser !== null && asyncIdUser !== undefined ? (
        <TouchableOpacity style={styles.customButton} onPress={() =>
          navigation.navigate('AttractionData', { id: item.id_attraction })}>
          <Text style={styles.buttonText}>More information</Text>
        </TouchableOpacity>
      ) : null}

    </View>
  );

  useEffect(() => {
    if (isFocused) {
      fetchData();
      getAsyncIdUser();
    }
  }, [isFocused]);

  const getAsyncIdUser = async () => {
    const idUser = await getData('id_user');
    setAsyncIdUser(idUser);
  };

  const fetchData = async () => {
    try {
      const response = await fetch(
        SITE + 'getAttractions.php',
        {
          method: 'GET',
          headers: {
            'Content-Type': 'application/json',
          },
        }
      );

      const data = await response.json();

      // Assuming the JSON structure is an array directly
      setAttractions(data);
      setLoading(false);
    } catch (error) {
      console.error('Error fetching data:', error);
    }
  };


  return (
    <>
      {asyncIdUser === null || asyncIdUser === undefined ? (
        <View style={styles.navContainer}>
          <Button
            title="Login"
            onPress={() =>
              navigation.navigate('Login')
            }
          />
        </View>
      ) : (
        <View style={styles.navContainer}>
          <Button
            title="Logout"
            onPress={async () => {
              await removeData('id_user');
              setAsyncIdUser(null);
              Alert.alert('Logout', 'You have been logged out!');
            }}
          />


          <Button title="Favourites" onPress={() => navigation.navigate('Favourites')} />
          <Button title="Update profile" onPress={() => navigation.navigate('UpdateUser')} />


        </View>
      )}
      {isLoading ? (
        <ActivityIndicator animating={true} size="large" style={{ opacity: 1 }} color="#00f" />
      ) : (
        <FlatList
          data={attractions}
          keyExtractor={(item) => item.id_attraction.toString()}
          renderItem={renderItem}
        />
      )}
    </>
  );
};

const AttractionDataScreen = ({ navigation, route }) => {
  const isFocused = useIsFocused();
  const [singleAttraction, setSingleAttractrion] = useState([]);
  const [isLoading, setLoading] = useState(true);
  const [asyncIdUser, setAsyncIdUser] = useState(null);


  const insertFavourite = async (itemId) => {
    try {
      const response = await fetch(
        SITE + 'insertFavourite.php',
        {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({ "id_attraction": itemId, "id_user": asyncIdUser }),
        });

      const result = await response.json();

      //navigation.navigate('Favourites', {});

      if (response.ok) {
        Alert.alert('Favourite', 'Favourite added successfully');
        fetchData();
      } else {
        Alert.alert('Error', result.message);
      }


    } catch (error) {
      console.error('Error adding item:', error);
    }
  };

  useEffect(() => {
    if (isFocused) {
      fetchData();
      getAsyncIdUser();
    }
  }, [isFocused]);

  const getAsyncIdUser = async () => {
    const idUser = await getData('id_user');
    setAsyncIdUser(idUser);
  };

  const fetchData = async () => {
    try {
      const idUser = await getData('id_user');

      const url = SITE + 'getAttractionData.php?id_attraction=' + route.params.id + '&id_user=' + idUser;
      const response = await fetch(

        url,
        {
          method: 'GET',
          headers: {
            'Content-Type': 'application/json',
          },
        }
      );

      const data = await response.json();

      // Assuming the JSON structure is an array directly
      setSingleAttractrion(data);
      setLoading(false);
    } catch (error) {
      console.error('Error fetching data:', error);
    }
  };


  return (
    <>
      {isLoading ? (

        <ActivityIndicator animating={true} size="large" style={{ opacity: 1 }} color="#00f" />
      ) : (
        <View style={styles.container}>
          <View style={styles.item}>
            <Text style={styles.big}>{singleAttraction.attraction_name}</Text>
          </View>

          <View style={styles.attractionItem}>
            <Text style={styles.data}>Type: {singleAttraction.type}</Text>
            <Text style={styles.data}>Organization: {singleAttraction.org_name}</Text>
            <Text style={styles.data}>City: {singleAttraction.city_name}</Text>
          </View>

          <View style={styles.item}>
            <Text style={styles.data}>{singleAttraction.description}</Text>
          </View>
          {!singleAttraction.isCityFavourite ? (
            <View style={styles.outsideItem}>
              <TouchableOpacity style={styles.deleteButton} onPress={() =>
                insertFavourite(singleAttraction.id_attraction)}>
                <Text style={styles.buttonText}>Add to favourites</Text>
              </TouchableOpacity>
            </View>
          ) : (null)
          }
        </View>
      )}
    </>
  );

};

const LoginScreen = ({ navigation }) => {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [isLoading, setLoading] = useState(true);


  const login = async () => {
    if (email === '' || password === '') {
      Alert.alert('Error', 'Fill all the fields!');
    }
    else if (password.length < 8) {
      Alert.alert('Error', 'Password too short!');
    }
    else {
      try {
        setLoading(true);

        const response = await fetch(
          SITE + 'login.php',
          {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
            },
            body: JSON.stringify({ email, password }),
          }
        );

        const result = await response.json();

        if (response.ok) {
          Alert.alert('Success', result.message);

          await storeData('id_user', result.id_user);
          await storeData('email', result.email);
          navigation.navigate('Home', {});

        } else {
          Alert.alert('Error', result.message);
        }
      } catch (error) {
        console.error('Error updating data:', error);
        Alert.alert('Error', 'Failed to update data.');
      }
      finally {
        setLoading(false);
      }
    }
  };

  return (
    <View style={styles.container}>
      <Text style={styles.title}>Login</Text>
      <TextInput
        style={styles.input}
        placeholder="Email"
        value={email}
        onChangeText={setEmail}
      />
      <TextInput
        style={styles.input}
        placeholder="Password"
        secureTextEntry
        value={password}
        onChangeText={setPassword}
      />
      <Button title="Login" onPress={login} />
    </View>
  )
};

const FavouritesScreen = ({ navigation }) => {

  const isFocused = useIsFocused();
  const [favourites, setFavourites] = useState([]);
  const [isLoading, setLoading] = useState(true);
  const [asyncIdUser, setAsyncIdUser] = useState(null);

  const deleteFavourite = async (itemId) => {
    try {
      await fetch(
        SITE + 'deleteFavourite.php',
        {
          method: 'DELETE',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({ "id_favourite": itemId }),
        });

      fetchData(asyncIdUser);
      Alert.alert('Favourite delete', 'You successfully deleted one of your favourite attractions!');
    } catch (error) {
      console.error('Error deleting item:', error);
    }
  };

  const renderItem = ({ item }) => (
    <View style={styles.item}>
      <Text style={styles.strong}>{item.attraction_name}</Text>
      <Text style={styles.data}>City: {item.city_name}</Text>

      <TouchableOpacity style={styles.deleteButton} onPress={() =>
        deleteFavourite(item.id_favourite)}>
        <Text style={styles.buttonText}>Delete</Text>
      </TouchableOpacity>

    </View>
  );

  useEffect(() => {
    if (isFocused) {
      getAsyncIdUser();
    }
  }, [isFocused]);

  const getAsyncIdUser = async () => {
    const idUser = await getData('id_user');
    setAsyncIdUser(idUser);
    fetchData(idUser);
  };

  const fetchData = async (userID) => {
    try {

      const response = await fetch(
        SITE + 'getFavourites.php?id_user=' + userID,
        {
          method: 'GET',
          headers: {
            'Content-Type': 'application/json',
          },
        }
      );

      const data = await response.json();

      console.log(data);
      // Assuming the JSON structure is an array directly
      setFavourites(data);
      setLoading(false);
    } catch (error) {
      console.error('Error fetching data:', error);
    }
  };


  return (
    <>
      {isLoading ? (
        <ActivityIndicator animating={true} size="large" style={{ opacity: 1 }} color="#00f" />
      ) : (
        favourites.message === 'Not found' ?
          (
            <View style={styles.container}>
              <Text style={styles.big}>You don't have any favorites yet.</Text>
            </View>
          ) :
          (
            <FlatList

              data={favourites}

              keyExtractor={(item) => item.id_attraction.toString()}

              renderItem={renderItem}
            />
          )

      )}
    </>
  )
};

const UpdateUserScreen = ({ navigation }) => {

  const isFocused = useIsFocused();
  const [formFirstname, setFormFirstname] = useState('');
  const [formLastname, setFormLastname] = useState('');
  const [userData, setUser] = useState([]);
  const [isLoading, setLoading] = useState(true);
  const [asyncIdUser, setAsyncIdUser] = useState(null);



  const update = async () => {
    if (formFirstname === '' || formLastname === '') {
      Alert.alert('Error', 'Fill all the fields!');
    }
    else {
      try {
        setLoading(true);

        const response = await fetch(
          SITE + 'updateUserData.php',
          {
            method: 'PATCH',
            headers: {
              'Content-Type': 'application/json',
            },
            body: JSON.stringify({ "id_user": asyncIdUser, "firstname": formFirstname, "lastname": formLastname }),
          }
        );

        const result = await response.json();
        
        console.log(result);

        if (response.ok) {
          Alert.alert('Success', result.message);
          fetchData(asyncIdUser);
        } else {
          Alert.alert('Error', result.message);
        }
      } catch (error) {
        console.error('Error updating data:', error);
        Alert.alert('Error', 'Failed to update data.');
      }
      finally {
        setLoading(false);
      }
    }
  };
  useEffect(() => {
    if (isFocused) {
      {
        getAsyncIdUser();
      }
    }
  }, [isFocused]);

  const getAsyncIdUser = async () => {
    const idUser = await getData('id_user');
    setAsyncIdUser(idUser);
    fetchData(idUser);
  };

  const fetchData = async (idUser) => {
    try {
      console.log(idUser);
      const response = await fetch(
        SITE + 'getUserData.php?id_user=' + idUser,
        {
          method: 'GET',
          headers: {
            'Content-Type': 'application/json',
          },
        }
      );

      const data = await response.json();

      console.log(data);


      setUser(data);

      setLoading(false);

    } catch (error) {
      console.error('Error fetching data:', error);
    }
  };


  return (
    <>
      {isLoading ? (
        <ActivityIndicator animating={true} size="large" style={{ opacity: 1 }} color="#00f" />
      ) : (
        <View style={styles.container}>
          <Text style={styles.title}>Firstname:</Text>
          <TextInput
            style={styles.input}
            placeholder={userData.firstname}
            onChangeText={setFormFirstname}
          />
          <Text style={styles.title}>Lastname:</Text>
          <TextInput
            style={styles.input}
            placeholder={userData.lastname}
            onChangeText={setFormLastname}
          />
          <Button title="Edit" onPress={update} />
        </View>
      )}
    </>
  )
};

const styles = StyleSheet.create({
  navContainer: {
    flexDirection: 'row',
    justifyContent: 'space-evenly',
    alignItems: 'center',
    backgroundColor: 'lightgray',
    paddingVertical: 10,

  },

  container: {
    flex: 1,
    padding: 26,
    justifyContent: 'center',

    marginTop: 16,
  },
  input: {
    height: 40,
    borderColor: 'gray',
    borderWidth: 1,
    marginBottom: 16,
    paddingHorizontal: 8,

  },
  outsideItem:{

    padding: 20,
    marginVertical: 8,
    marginHorizontal: 20,
    alignItems: 'center',
  },
  item: {
    backgroundColor: '#e9e9e9',
    padding: 20,
    marginVertical: 8,
    marginHorizontal: 20,
    alignItems: 'center',
    borderColor: '#cecece',
    borderWidth: 5,
    borderRadius: 20
  },
  attractionItem: {
    backgroundColor: '#e9e9e9',
    padding: 20,
    marginVertical: 8,
    marginHorizontal: 20,

    borderColor: '#cecece',
    borderWidth: 5,
    borderRadius: 20
  },
  strong: {
    fontSize: 20,
    fontWeight: 'bold',
  },
  data: {
    fontSize: 16,
  },
  big: {
    fontSize: 30,
    fontWeight: 'bold'
  },
  customButton:
  {
    backgroundColor: '#00b377',
    marginTop: 10,
    padding: 8,
    borderColor: '#cecece',
    borderWidth: 5,
    borderRadius: 20
  },
  deleteButton:
  {
    backgroundColor: '#f00',
    marginTop: 10,
    padding: 8,
    borderColor: '#cecece',
    borderWidth: 5,
    borderRadius: 20,
  },
  buttonText: {
    color: '#fff'

  }

});


export default MyStack;
